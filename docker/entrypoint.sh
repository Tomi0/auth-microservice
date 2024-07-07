echo "Starting entrypoint"

if [ "${USER_ID}" ]; then
    echo "Changin auth-microservice UID and GID to ${USER_ID}:${GROUP_ID}"
    usermod -u "USER_ID" auth-microservice
    groupmod -g "GROUP_ID" auth-microservice
    chown -R auth-microservice:auth-microservice /var/www
fi

if [ "$APP_ENV" = 'production' ]; then
    su auth-microservice --command "php api/artisan optimize:clear"
    su auth-microservice --command "php api/artisan optimize"
    su auth-microservice --command "php api/artisan config:cache"
    su auth-microservice --command "php api/artisan event:cache"
    su auth-microservice --command "php api/artisan route:cache"
    su auth-microservice --command "php api/artisan view:cache"
    su auth-microservice --command "php api/artisan icons:cache"
    su auth-microservice --command "php api/artisan filament:cache"
    su auth-microservice --command "php api/artisan migrate --force"
fi

service nginx start
php-fpm
