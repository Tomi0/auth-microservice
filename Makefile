
install:
	make composer-install
	@if [ ! -f .env ]; then cp .env.example .env; fi
	make generate-keys

composer-install:
	docker run --rm -it -v $(shell pwd):/app -w /app --user $(shell id -u):$(shell id -g) composer:2.2.7 composer install --ignore-platform-reqs --no-ansi

start:
	USER_ID=${shell id -u} GROUP_ID=${shell id -g} docker compose up --build -d

stop:
	USER_ID=${shell id -u} GROUP_ID=${shell id -g} docker compose down

test:
	docker exec --user $(shell id -u):$(shell id -g) auth-php ./vendor/bin/phpunit

generate-keys:
	openssl genrsa -out storage/app/signing_keys/key.pem 2048
	openssl rsa -in storage/app/signing_keys/key.pem -outform PEM -pubout -out storage/app/signing_keys/public.pem
