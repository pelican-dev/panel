# syntax=docker.io/docker/dockerfile:1.7-labs
# Pelican Production Dockerfile

# ================================
# Stage 1: Build PHP Base Image
# ================================
FROM --platform=$TARGETOS/$TARGETARCH php:8.3-fpm-alpine AS base

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions bcmath gd intl zip opcache pcntl posix pdo_mysql

RUN rm /usr/local/bin/install-php-extensions

# ================================
# Stage 2-1: Composer Install
# ================================
FROM --platform=$TARGETOS/$TARGETARCH base AS composer

WORKDIR /build

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy bare minimum to install Composer dependencies
COPY composer.json composer.lock ./

RUN composer install --no-dev --no-interaction --no-autoloader --no-scripts

# ================================
# Stage 2-2: Yarn Install
# ================================
FROM --platform=$TARGETOS/$TARGETARCH node:20-alpine AS yarn

WORKDIR /build

# Copy bare minimum to install Yarn dependencies
COPY package.json yarn.lock ./

RUN yarn config set network-timeout 300000 \
    && yarn install --frozen-lockfile

# ================================
# Stage 3-1: Composer Optimize
# ================================
FROM --platform=$TARGETOS/$TARGETARCH composer AS composerbuild

# Copy full code to optimize autoload
COPY --exclude=Caddyfile --exclude=docker/ . ./

RUN composer dump-autoload --optimize

# ================================
# Stage 3-2: Build Frontend Assets
# ================================
FROM --platform=$TARGETOS/$TARGETARCH yarn AS yarnbuild

WORKDIR /build

# Copy full code
COPY --exclude=Caddyfile --exclude=docker/ . ./
COPY --from=composer /build .

RUN yarn run build

# ================================
# Stage 4: Build Final Application Image
# ================================
FROM --platform=$TARGETOS/$TARGETARCH base AS final

WORKDIR /var/www/html

# Install additional required libraries
RUN apk update && apk add --no-cache \
    caddy ca-certificates supervisor supercronic

COPY Caddyfile /etc/caddy/Caddyfile
COPY --from=composerbuild /build .
COPY --from=yarnbuild /build/public ./public

# Set permissions for Laravel directories
RUN mkdir -p /pelican-data /var/run/supervisord /etc/supercronic \
    && chmod -R 755 /pelican-data storage bootstrap/cache /var/run/supervisord \
    && chown -R www-data:www-data /pelican-data storage bootstrap/cache /var/run/supervisord \
    # Only database folder permissions are needed to link to sqlite database, no deeper
    && chmod 755 database \
    && chown www-data:www-data database \
    # Add Laravel scheduler to crontab
    && echo "* * * * * php /var/www/html/artisan schedule:run" > /etc/supercronic/crontab

# Configure Supervisor
COPY docker/supervisord.conf /etc/supervisord.conf

COPY docker/entrypoint.sh ./docker/entrypoint.sh

HEALTHCHECK --interval=5m --timeout=10s --start-period=5s --retries=3 \
  CMD curl -f http://localhost/up || exit 1

EXPOSE 80 443

VOLUME /pelican-data

USER www-data

ENTRYPOINT [ "/bin/ash", "docker/entrypoint.sh" ]
CMD [ "supervisord", "-n", "-c", "/etc/supervisord.conf" ]
