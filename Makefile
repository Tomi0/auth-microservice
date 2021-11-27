UID := $(shell id -u)
GID := $(shell id -g)

.PHONY: serve
serve:
	env UID=${UID} GID=${GID} docker-compose up -d

.PHONY: down
down:
	env UID=${UID} GID=${GID} docker-compose down

.PHONY: composer-exec
composer-exec:
	docker run --rm --interactive --tty --volume $(PWD):/app --user $(UID):$(GID) composer $(cmd)

.PHONY: run
run:
	env UID=${UID} GID=${GID} docker-compose exec auth-php $(cmd)
