FROM dunglas/frankenphp:1.4.4-php8.4.4 AS base

# Install dependencies
RUN install-php-extensions \
    bcmath \
    exif \
    gd \
    intl \
    mbstring \
    opcache \
    pcntl \
    pdo_pgsql \
    sockets \
    zip

WORKDIR /app

COPY --link --chmod=777 --from=composer:2.8.6 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --link ./docker/app/Caddyfile /etc/caddy/Caddyfile
COPY --link ./docker/app/local.ini "$PHP_INI_DIR/conf.d/90-local.ini"

FROM base AS dev
RUN install-php-extensions \
    xdebug \
    && cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY --link ./docker/app/local.dev.ini "$PHP_INI_DIR/conf.d/91-local.env.ini"
COPY --link --chmod=755 docker/app/entrypoint.dev.sh /usr/local/bin/docker-entrypoint
ENTRYPOINT ["docker-entrypoint"]
# @see https://github.com/dunglas/frankenphp/blob/0b4a427cac1da044518ada44345bbafd98a0bc04/Dockerfile#L26
# Needs to be copied because of the ENTRYPOINT instruction which looks to override it.
CMD ["--config", "/etc/caddy/Caddyfile", "--adapter", "caddyfile"]
ARG UID
ARG GID
RUN groupadd --gid ${GID?} host \
    && useradd --uid ${UID?} --gid ${GID?} --create-home host \
    # https://frankenphp.dev/docs/docker/#running-as-a-non-root-user
    && chown -R host:host /data/caddy \
    && chown -R host:host /config/caddy
USER host

FROM base AS prod
ENV APP_ENV=prod
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --link ./docker/app/local.prod.ini "$PHP_INI_DIR/conf.d/local.env.ini"

COPY --link ./composer.* ./
RUN composer install \
    --no-autoloader \
    --no-scripts \
    --no-cache \
    --no-dev \
    --prefer-dist

COPY --link ./importmap.php ./
COPY --link assets assets
COPY --link public public

COPY --link bin bin
COPY --link config config
COPY --link src src
COPY --link migrations migrations
COPY --link templates templates
COPY --link translations translations
COPY --link .env ./
RUN composer dump-autoload --classmap-authoritative \
    && composer auto-scripts
