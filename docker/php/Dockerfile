FROM php:7.1-fpm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update
RUN apt-get install -y libpq-dev netcat git zip unzip

RUN docker-php-ext-install pdo_pgsql

ENV COMPOSER_ALLOW_SUPERUSER 1

ADD php.ini /usr/local/etc/php/conf.d/

WORKDIR /app