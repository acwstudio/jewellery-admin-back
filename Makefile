TARGET_BRANCH ?= master

up:
	docker-compose up -d

build:
	docker-compose build --no-cache
	docker-compose up -d
	docker-compose run --rm -u sail cli sh -c "COMPOSER_PROCESS_TIMEOUT=3600 composer install --prefer-dist && php artisan key:generate && php artisan migrate && php vendor/bin/openapi app -o openapi.yaml"
	docker-compose down

swagger:
	docker-compose run --rm -u sail cli sh -c "php /var/www/vendor/bin/openapi app -o openapi.yaml"

phpcbf:
	docker-compose run --rm -u sail cli sh -c "./bin/phpcbf ${TARGET_BRANCH}"

phpstan:
	docker-compose run --rm -u sail cli sh -c "./bin/phpstan ${TARGET_BRANCH}"

phpunit:
	docker-compose run --rm -u sail cli sh -c "php ./vendor/bin/phpunit -v --stop-on-failure"

sh:
	docker-compose run --rm -u sail cli sh

migrate:
	docker-compose run --rm -u sail cli sh -c "php artisan migrate"
