FROM composer:2.5.7 as composer

FROM php:8.2-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y git \
    libonig-dev \
    libzip-dev \
    zip \
    nginx
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN docker-php-ext-install pdo_mysql mbstring zip

COPY ./docker/production/nginx /etc/nginx/conf.d
COPY --chown=www-data:www-data . /var/www
RUN rm -rf /var/www/bootstrap/cache/*.php
RUN rm -rf /var/www/storage/logs/*.php

RUN cd /var/www && /usr/bin/composer install

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

EXPOSE 9000

COPY docker/production/entrypoint.sh /etc/supervisor/conf.d/supervisord.conf

ENV APP_NAME="Authentication microservice"
ENV APP_ENV=production
ENV APP_KEY="base64:yAjw7noaOz+Qq1/1yTPx9HZ8ScT5LVHjNrbtiq7z5lk="
ENV APP_DEBUG=false
ENV LOG_CHANNEL=daily
ENV LOG_DEPRECATIONS_CHANNEL=null
ENV LOG_LEVEL=debug
ENV FILESYSTEM_DRIVER=local
ENV QUEUE_CONNECTION=sync
ENV SESSION_DRIVER=file
ENV SESSION_LIFETIME=120

COPY docker/production/entrypoint.sh /etc/entrypoint.sh

ENTRYPOINT ["sh", "/etc/entrypoint.sh"]
