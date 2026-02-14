# =============================================================================
# Stage 1: Builder - Install dependencies and initialize application
# =============================================================================
ARG PHP_VERSION=8.2
FROM yiisoftware/yii2-php:${PHP_VERSION}-apache AS builder

WORKDIR /app

# Copy composer files first to leverage Docker layer caching
COPY composer.json composer.lock ./

# Install dependencies (including dev for debug toolbar and gii)
RUN composer install --no-interaction

# Copy full application source
COPY . .

# Create required directories and initialize Yii2 application
# php init generates: index.php, yii console script, *-local.php configs, cookie validation keys
RUN mkdir -p backend/runtime backend/web/assets frontend/runtime frontend/web/assets console/runtime && \
    php init --env=Development --overwrite=All --delete=All && \
    composer dump-autoload --optimize

# =============================================================================
# Stage 2: Production - Optimized runtime image
# =============================================================================
FROM yiisoftware/yii2-php:${PHP_VERSION}-apache

WORKDIR /app

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql bcmath

# Configure Apache document root for Yii2 Backend
RUN sed -i -e 's|/app/web|/app/backend/web|g' /etc/apache2/sites-available/000-default.conf && \
    a2enmod rewrite

# Copy entrypoint script and fix line endings (CRLF -> LF for Windows compatibility)
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh

# Copy built application from builder stage
COPY --from=builder /app /app

# Set proper permissions for runtime directories
RUN chown -R www-data:www-data backend/runtime backend/web/assets console/runtime && \
    chmod -R 777 backend/runtime backend/web/assets console/runtime

# Default environment variables (fallbacks for local/test environments)
ENV APP_TYPE=backend \
    DB_HOST=mysql \
    DB_NAME=inventory_system \
    DB_USER=yii2user \
    DB_PASSWORD=yii2pass

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=3s --start-period=60s --retries=5 \
    CMD curl -f http://localhost/ || exit 1

ENTRYPOINT ["docker-entrypoint.sh"]
