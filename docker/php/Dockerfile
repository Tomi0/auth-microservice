FROM composer:2.2.7 as composer

FROM php:8.1-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update

RUN docker-php-ext-install pdo_mysql

EXPOSE 9000
