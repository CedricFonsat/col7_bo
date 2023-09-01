DOCKER=docker compose
COMPOSER=symfony composer

composer-install:
	$(COMPOSER) install

clean: ## restet your symfony project
	rm -Rf bin config migrations public src tests translations var vendor .env .env.test .gitignore composer.* symfony.lock phpunit* templates

docker-install: Dockerfile docker-compose.yaml clean ## Reset and install your environment
	$(DOCKER) down
	$(DOCKER) up -d --build
	$(DOCKER) ps
	$(DOCKER) logs -f

docker-up: ## Start the docker container
	$(DOCKER) down
	$(DOCKER) up -d

docker-build: ## Start the docker container
	$(DOCKER) build

docker-sh: ## Connect to the docker container
	docker exec -it www_docker_symfony bash