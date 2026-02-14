#!/bin/bash
set -e

APP_TYPE=${APP_TYPE:-backend}

echo "=== Yii2 Docker Entrypoint (${APP_TYPE}) ==="

# Ensure runtime directories have proper permissions
mkdir -p /app/${APP_TYPE}/runtime /app/${APP_TYPE}/web/assets /app/console/runtime
chmod -R 777 /app/${APP_TYPE}/runtime /app/${APP_TYPE}/web/assets /app/console/runtime

# Wait for MySQL to be ready
echo ">>> Waiting for MySQL at ${DB_HOST:-mysql}..."
MAX_TRIES=30
TRIES=0
until php -r "
    try {
        \$dsn = 'mysql:host=' . getenv('DB_HOST') . ';port=3306';
        new PDO(\$dsn, getenv('DB_USER'), getenv('DB_PASSWORD'));
        echo 'MySQL is ready!' . PHP_EOL;
    } catch (Exception \$e) {
        exit(1);
    }
" 2>/dev/null; do
    TRIES=$((TRIES + 1))
    if [ $TRIES -ge $MAX_TRIES ]; then
        echo ">>> WARNING: MySQL did not become ready after ${MAX_TRIES} attempts. Continuing anyway..."
        break
    fi
    echo ">>> Waiting for MySQL... (attempt ${TRIES}/${MAX_TRIES})"
    sleep 2
done

# Run migrations only from backend to avoid race conditions between frontend/backend
if [ "$APP_TYPE" = "backend" ]; then
    echo ">>> Running RBAC migrations (creates auth tables)..."
    cd /app && php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0 || echo ">>> WARNING: RBAC migrations had issues."

    echo ">>> Running application migrations..."
    cd /app && php yii migrate --interactive=0 || echo ">>> WARNING: App migrations had issues, check logs."
fi

echo ">>> Starting Apache for ${APP_TYPE}..."
exec apache2-foreground
