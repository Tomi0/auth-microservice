FROM node:21.6-alpine as builder

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

WORKDIR /var/www
RUN rm -rf /var/www/html

RUN mkdir /var/www/frontend
RUN mkdir /var/www/api

COPY --chown=www-data:www-data --from=builder /app/dist/auth-microservice/browser /var/www/frontend
COPY --chown=www-data:www-data . /var/www/api

COPY ./docker/production/nginx/default.conf /etc/nginx/sites-enabled/default

RUN cd /var/www/api && /usr/bin/composer install

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN chown www-data:www-data -R /var/www

EXPOSE 9000

COPY docker/production/entrypoint.sh /etc/entrypoint.sh

ENTRYPOINT ["sh", "/etc/entrypoint.sh"]
