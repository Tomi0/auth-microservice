FROM composer:2.2.7 as composer

FROM php:8.2-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get -y install git \
    libonig-dev \
    libzip-dev \
    zip

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN docker-php-ext-install pdo_mysql mbstring zip

EXPOSE 9000
