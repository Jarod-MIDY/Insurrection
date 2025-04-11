.PHONY: validate install update deps phpcs phpcbf phpmin-compatibility phpmax-compatibility phpstan analyze tests testdox ci clean

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
start:
	$(call header,Starting Docker)
	@docker compose up -d

stop:
	$(call header,Stoping Docker)
	@docker compose down

restart: stop start
	$(call header,Restarting Docker)

#~ Symfony commands
cc:
	$(call header,Cleaning Symfony Cache)
	@bin/console cache:clear

compile:
	$(call header,Compile assets)
	@bin/console asset-map:compile

migrate:
	$(call header,Cleaning Symfony Cache)
	@bin/console doctrine:migrations:execute # not sure is the right command

#~ Vendor binaries dependencies
vendor/bin/php-cs-fixer:
vendor/bin/phpstan:
vendor/bin/phpunit:
vendor/bin/phpcov:

#~ Report directories dependencies
build/reports/phpunit:
	@mkdir -p build/reports/phpunit

build/reports/phpcs:
	@mkdir -p build/reports/cs

build/reports/phpstan:
	@mkdir -p build/reports/phpstan

#~ main commands
deps: composer.json # auto + manual
	$(call header,Checking Dependencies)
	@XDEBUG_MODE=off ./vendor/bin/composer-dependency-analyser --config ./ci/composer-dependency-analyser.php # for shadow, unused required dependencies and ext-* missing dependencies

phpcs: vendor/bin/php-cs-fixer build/reports/phpcs # auto + manual
	$(call header,Checking Code Style)
	@./vendor/bin/php-cs-fixer check -v --diff

phpcbf: vendor/bin/php-cs-fixer # manual
	$(call header,Fixing Code Style)
	@./vendor/bin/php-cs-fixer fix -v

phpmin-compatibility: vendor/bin/phpstan build/reports/phpstan # auto + manual
	$(call header,Checking PHP min compatibility)
	@XDEBUG_MODE=off ./vendor/bin/phpstan analyse --configuration=./ci/phpmin-compatibility.neon --error-format=checkstyle > ./build/reports/phpstan/phpmin-compatibility.xml

phpmax-compatibility: vendor/bin/phpstan build/reports/phpstan # auto + manual
	$(call header,Checking PHP max compatibility)
	@XDEBUG_MODE=off ./vendor/bin/phpstan analyse --configuration=./ci/phpmax-compatibility.neon --error-format=checkstyle > ./build/reports/phpstan/phpmax-compatibility.xml

phpstan: vendor/bin/phpstan build/reports/phpstan # auto
	$(call header,Running Static Analyze)
	@XDEBUG_MODE=off ./vendor/bin/phpstan analyse --error-format=checkstyle > ./build/reports/phpstan/phpstan.xml

analyze: vendor/bin/phpstan build/reports/phpstan # manual
	$(call header,Running Static Analyze - Pretty tty format)
	@XDEBUG_MODE=off ./vendor/bin/phpstan analyse --error-format=table -v --generate-baseline

tests: vendor/bin/phpunit build/reports/phpunit # auto + manual
	$(call header,Running Unit Tests)
	@XDEBUG_MODE=coverage ./vendor/bin/phpunit --testsuite=unit --coverage-clover=./build/reports/phpunit/clover.xml --log-junit=./build/reports/phpunit/unit.xml --coverage-php=./build/reports/phpunit/unit.cov --coverage-html=./build/reports/coverage/ --fail-on-warning

integration: vendor/bin/phpunit build/reports/phpunit #manual
	$(call header,Running Integration Tests)
	@XDEBUG_MODE=coverage ./vendor/bin/phpunit --testsuite=integration --fail-on-warning

testdox: vendor/bin/phpunit # manual
	$(call header,Running Unit Tests (Pretty format))
	@XDEBUG_MODE=coverage ./vendor/bin/phpunit --testsuite=unit --fail-on-warning --testdox

clean: # manual
	$(call header,Cleaning previous build)
	@if [ "$(shell ls -A ./build)" ]; then rm -rf ./build/*; fi; echo " done"

ci: clean validate deps install deps phpcs phpmin-compatibility phpmax-compatibility analyze
