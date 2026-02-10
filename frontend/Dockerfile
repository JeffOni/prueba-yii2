# =============================================================================
# Stage 1: Builder - Instalación de dependencias y configuración
# =============================================================================
ARG PHP_VERSION=8.2
FROM yiisoftware/yii2-php:${PHP_VERSION}-apache AS builder

WORKDIR /app

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql bcmath

# Copiar solo archivos de dependencias primero (para aprovechar cache de Docker)
COPY composer.json composer.lock* ./

# Instalar dependencias de Composer (sin dev dependencies en producción)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts || true

# =============================================================================
# Stage 2: Production - Imagen final optimizada
# =============================================================================
FROM yiisoftware/yii2-php:${PHP_VERSION}-apache

WORKDIR /app

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql bcmath

# Configurar document root de Apache para Yii2 Frontend
RUN sed -i -e 's|/app/web|/app/frontend/web|g' /etc/apache2/sites-available/000-default.conf && \
    a2enmod rewrite

# Copiar dependencias instaladas desde builder (si existen)
COPY --from=builder /app/vendor ./vendor 2>/dev/null || true

# Configurar permisos para runtime y assets
RUN mkdir -p frontend/runtime frontend/web/assets && \
    chown -R www-data:www-data frontend/runtime frontend/web/assets && \
    chmod -R 775 frontend/runtime frontend/web/assets

# Exponer puerto 80
EXPOSE 80

# Healthcheck para verificar que Apache esté funcionando
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/ || exit 1
