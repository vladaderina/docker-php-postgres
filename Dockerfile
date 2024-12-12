ARG XDEBUG_VERSION="xdebug-2.9.0"

FROM php:8.2-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN yes | pecl install ${XDEBUG_VERSION} \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

COPY php.ini /usr/local/etc/php/

WORKDIR /var/www/html