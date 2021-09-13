ARG PHPTYPE=fpm
FROM php:8.0-$PHPTYPE as php

ARG UID=1000
ARG GID=1000

RUN mkdir -p /var/www && mkdir -p /home/www-data/.composer

RUN usermod -u $UID www-data && groupmod -g $GID www-data
RUN chown -R www-data:www-data /var/www && chown -R www-data:www-data /home/www-data

WORKDIR /var/www

USER www-data




FROM php as tool

USER root

RUN apt update && apt install -y git zlib1g-dev libzip-dev unzip \
    && docker-php-ext-install zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

USER www-data




FROM php as dev

USER www-data

COPY . /var/www




FROM composer as composer

USER www-data

COPY composer.json composer.json
COPY composer.lock composer.lock
COPY symfony.lock symfony.lock

RUN composer install --no-scripts --no-dev --no-autoloader --no-interaction

COPY . /app

RUN composer dump-autoload




FROM php as prod

COPY --from=composer /app/vendor /var/www/vendor

COPY . /var/www