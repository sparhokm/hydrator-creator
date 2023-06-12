cli=docker compose run --rm php-cli

init: docker-down docker-up docker-pull docker-build project-init
up: docker-up
down: docker-down
restart: down up project-init

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build --pull

project-init:
	$(cli) composer install

cli:
	$(cli) $(filter-out $@,$(MAKECMDGOALS))

php:
	$(cli) php $(filter-out $@,$(MAKECMDGOALS))

composer:
	$(cli) composer $(filter-out $@,$(MAKECMDGOALS))

check: fixcs psalm test

fixcs:
	$(cli) composer fixcs

psalm:
	$(cli) composer psalm

test:
	$(cli) composer test

test-coverage:
	$(cli) composer test-coverage
