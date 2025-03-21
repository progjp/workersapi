start:
	docker compose up -d
stop:
	docker compose down
sh:
	docker compose exec -it workersapi bash
init:
	make start
	docker compose exec -it workersapi composer install
test:
	docker-compose up -d db-test
	docker compose exec -it workersapi bin/phpunit
	docker compose down db-test
remove-all-data:
	docker compose down -v
