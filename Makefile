
composer-install:
	USER_ID=${shell id -u} GROUP_ID=${shell id -g} docker exec auth-php composer install

start:
	USER_ID=${shell id -u} GROUP_ID=${shell id -g} docker compose up --build -d

stop:
	USER_ID=${shell id -u} GROUP_ID=${shell id -g} docker compose down

test:
	USER_ID=${shell id -u} GROUP_ID=${shell id -g} docker exec auth-php ./vendor/bin/phpunit

generate-keys:
	openssl genrsa -out storage/app/signing_keys/key.pem 2048
	openssl rsa -in storage/app/signing_keys/key.pem -outform PEM -pubout -out storage/app/signing_keys/public.pem
