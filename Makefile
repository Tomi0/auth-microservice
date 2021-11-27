UID := $(shell id -u)
GID := $(shell id -g)
run := env UID=${UID} GID=${GID} docker-compose exec auth-php

.SILENT: test run

.PHONY: serve
serve:
	env UID=${UID} GID=${GID} docker-compose up -d

.PHONY: build
build:
	env UID=${UID} GID=${GID} docker-compose build

.PHONY: down
down:
	env UID=${UID} GID=${GID} docker-compose down

.PHONY: composer-exec
composer-exec:
	docker run --rm --interactive --tty --volume $(PWD):/app --user $(UID):$(GID) composer $(cmd)

.PHONY: run
run:
	$(run) $(cmd)

.PHONY: test
test:
	$(run) ./vendor/bin/phpunit --testdox --configuration ./phpunit.xml

.PHONY: clean
clean:
	$(run) php artisan cache:clear
	$(run) php artisan config:clear
	$(run) php artisan route:clear

migrate:
	$(run) php artisan migrate

migrate-fresh:
	$(run) php artisan migrate:fresh

migrate-rollback:
	$(run) php artisan migrate:rollback
