# Dockerfile for PHP 8.2
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    zlib1g-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Grant write permissions to var/log and var/cache
RUN mkdir -p var/log var/cache && \
    chown -R www-data:www-data var/log var/cache && \
    chmod -R 777 var/log var/cache

# Expose the PHP port
EXPOSE 9000