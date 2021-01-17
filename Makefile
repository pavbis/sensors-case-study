.DEFAULT_GOAL := help
.SILENT:
.PHONY: vendor

## Colors
COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

## Help
help:
	printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	printf " make [target]\n\n"
	printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	awk '/^[a-zA-Z\-\_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

## Start containers.
up:
	docker-compose up -d
.PHONY: up

## Stop containers.
down:
	docker-compose down
.PHONY: down

## Rebuild containers.
rebuild:
	docker-compose down -v --remove-orphans
	docker-compose rm -vsf
	docker-compose up -d --build
.PHONY: rebuild

## Seed the database.
seed-database:
	docker-compose exec php ./bin/console doctrine:database:drop --force
	docker-compose exec php ./bin/console doctrine:database:create
	docker-compose exec php ./bin/console doctrine:migrations:migrate -n
.PHONY: seed-database

## Run qa tools.
qa:static-code-analyzer run-unit-tests
.PHONY: qa

## Run unit tests.
run-unit-tests:
	docker-compose run --rm php bin/phpunit
.PHONY: run-unit-tests

## Run static code analyzer.
static-code-analyzer:
	docker-compose run --rm php vendor/bin/phpstan analyse src
.PHONY: static-code-analyzer

