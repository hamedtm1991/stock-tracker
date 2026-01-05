FROM php:8.4-fpm

# Install system packages required by GD and ZIP
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libjpeg-dev libfreetype6-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip gd

COPY docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

# Set working directory
WORKDIR /var/www

# Configure PHP-FPM to listen on TCP port 9000
RUN sed -i 's/listen = .*/listen = 9000/' /usr/local/etc/php-fpm.d/www.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Expose port 9000 (internal for Nginx)
EXPOSE 9000
