UID := $(shell id -u)
GID := $(shell id -g)

.PHONY: serve
serve:
	env UID=${UID} GID=${GID} docker-compose up -d

down:
	env UID=${UID} GID=${GID} docker-compose down
