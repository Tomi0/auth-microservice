UID := $(shell id -u)
GID := $(shell id -g)

.PHONY: serve
serve:
	env UID=${UID} GID=${GID} docker-compose up -d

down:
	env UID=${UID} GID=${GID} docker-compose down

composer-exec:
	docker run --rm --interactive --tty --volume $(PWD):/app --user $(UID):$(GID) composer $(cmd)