.PHONY: validate install update up down restart templates/check templates/fix php/check php/fix php/stan sass cache/clear cc db/migration db/update ci

COMPOSER_BIN := composer

define header =
    @if [ -t 1 ]; then printf "\n\e[37m\e[100m  \e[104m $(1) \e[0m\n"; else printf "\n### $(1)\n"; fi
endef

#~ Composer dependency
validate:
	$(call header,Composer Validation)
	@${COMPOSER_BIN} validate

install:
	$(call header,Composer Install)
	@${COMPOSER_BIN} install

update:
	$(call header,Composer Update)
	@${COMPOSER_BIN} update
	@${COMPOSER_BIN} bump --dev-only

composer.lock: install

#~ Docker commands
up:
	$(call header,Starting Docker)
	docker compose up -d

down:
	$(call header,Stoping Docker)
	docker compose down

restart: down up
	$(call header,Restarting Docker)

#~ Templates
templates/check:
	$(call header,Checking templates)
	./vendor/bin/twig-cs-fixer lint

templates/fix:
	$(call header,Fixing templates)
	./vendor/bin/twig-cs-fixer lint --fix

#~ PHP Commands
php/check:
	$(call header,Running check with php-cs-fixer)
	./vendor/bin/php-cs-fixer fix -v --diff --dry-run --config=.php-cs-fixer.dist.php

php/fix:
	$(call header,Running fix with php-cs-fixer)
	./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php

php/stan:
	$(call header,Running phpstan)
	./vendor/bin/phpstan analyse

#~ Symfony commands
sass:
	$(call header,Start Watching SASS)
	docker compose exec app bin/console sass:build --watch

sass/build:
	$(call header,Building SASS)
	docker compose exec app bin/console sass:build

cache/clear:
	$(call header,Cache Clear)
	docker compose exec app bin/console cache:clear
cc: cache/clear

db/migration:
	$(call header,Creating new migration)
	docker compose exec app bin/console make:migration

db/update:
	$(call header,Migrating database)
	docker compose exec app bin/console doctrine:migrations:migrate --no-interaction --all-or-nothing

front/clear-assets:
	$(call header,Cache Clear)
	docker compose exec app rm -rf /public/assets

front/compile:
	$(call header,Compiling assets)
	docker compose exec app bin/console asset-map:compile

#~ CI commands
ci: validate php/check php/stan templates/check
