FROM node:21.6-alpine AS builder

RUN mkdir /app

COPY resources/frontend /app

RUN cd /app && npm install && npm run build --prod

FROM php:8.2-fpm

COPY --from=composer:2.5.7 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y git \
    libonig-dev \
    libzip-dev \
    zip \
    nginx

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN docker-php-ext-install pdo_mysql mbstring zip

RUN useradd auth-microservice -m -s /bin/bash

WORKDIR /var/www
RUN rm -rf /var/www/html

RUN mkdir /var/www/frontend
RUN mkdir /var/www/api

COPY --chown=auth-microservice:auth-microservice --from=builder /app/dist/auth-microservice/browser /var/www/frontend
COPY --chown=auth-microservice:auth-microservice . /var/www/api

COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/sites-enabled/default
COPY docker/nginx/backend.conf /etc/nginx/sites-enabled/backend.conf
COPY ./docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN cd /var/www/api && /usr/bin/composer install --optimize-autoloader --no-dev

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN chown auth-microservice:auth-microservice -R /var/www

EXPOSE 80
EXPOSE 8080

COPY docker/entrypoint.sh /etc/entrypoint.sh

ENTRYPOINT ["sh", "/etc/entrypoint.sh"]
