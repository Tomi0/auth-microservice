
install:
	make composer-install
	make npm-install
	@if [ ! -f .env ]; then cp .env.example .env; fi
	make generate-keys

npm-install:
	docker run --rm -it -v $(shell pwd)/resources/frontend:/app -w /app --user $(shell id -u):$(shell id -g) node:21.6-alpine npm install

composer-install:
	docker run --rm -it -v $(shell pwd):/app -w /app --user $(shell id -u):$(shell id -g) composer:2.2.7 composer install --ignore-platform-reqs --no-ansi

build:
	USER_ID=${shell id -u} GROUP_ID=${shell id -g} docker compose build

start:
	USER_ID=${shell id -u} GROUP_ID=${shell id -g} docker compose up -d

stop:
	USER_ID=${shell id -u} GROUP_ID=${shell id -g} docker compose down

test:
	docker exec --user $(shell id -u):$(shell id -g) auth-php ./vendor/bin/phpunit

generate-keys:
	openssl genrsa -out storage/app/signing_keys/key.pem 2048
	openssl rsa -in storage/app/signing_keys/key.pem -outform PEM -pubout -out storage/app/signing_keys/public.pem

bash:
	docker exec -it --user $(shell id -u):$(shell id -g) auth-php /bin/bash
