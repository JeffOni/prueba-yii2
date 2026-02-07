ARG PHP_VERSION=8.2
FROM yiisoftware/yii2-php:${PHP_VERSION}-apache

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/backend/web|g' /etc/apache2/sites-available/000-default.conf

# Install additional PHP extensions
RUN docker-php-ext-install pdo pdo_mysql bcmath
