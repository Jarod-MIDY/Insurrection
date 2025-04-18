#!/usr/bin/env sh
# @see https://github.com/dunglas/symfony-docker/blob/bfdd75e73ffcdce57f0f9f883029b57629549195/frankenphp/docker-entrypoint.sh
set -e

if [ -z "$(ls -A 'vendor/' 2>/dev/null)" ]; then
    composer install --prefer-dist --no-progress --no-interaction
fi

if [ -z "$(ls -A 'assets/vendor/' 2>/dev/null)" ]; then
    ./bin/console importmap:install
fi

./bin/console doctrine:database:create --no-interaction --if-not-exists
# ./bin/console doctrine:migrations:migrate --no-interaction --all-or-nothing

exec docker-php-entrypoint "$@"
