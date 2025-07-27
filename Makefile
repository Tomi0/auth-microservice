
install:
	make composer-install
	make npm-install
	@if [ ! -f .env ]; then cp .env.example .env; fi
	make build

npm-install:
	docker run --rm -it -v $(shell pwd)/resources/frontend:/app -w /app --user $(shell id -u):$(shell id -g) node:21.6-alpine npm install

composer-install:
	docker run --rm -it -v $(shell pwd):/app -w /app --user $(shell id -u):$(shell id -g) composer:2.2.7 composer install --ignore-platform-reqs --no-ansi

build:
	docker buildx build -t auth-microservice:latest .

start:
	USER_ID=${shell id -u} GROUP_ID=${shell id -g} docker compose up -d

stop:
	USER_ID=${shell id -u} GROUP_ID=${shell id -g} docker compose down

test:
	docker exec -it --user $(shell id -u):$(shell id -g) -w /var/www/api auth ./vendor/bin/phpunit --testdox

bash:
	docker exec -it auth /bin/bash
