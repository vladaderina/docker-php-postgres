FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

# Установка Composer (опционально)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Установка Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Установка рабочей директории
WORKDIR /var/www/html