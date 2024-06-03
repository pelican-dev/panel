# Pelican Production Dockerfile

FROM node:20-alpine AS yarn
WORKDIR /app

#FROM --platform=$TARGETOS/$TARGETARCH node:20-alpine AS yarn

COPY . ./

RUN --mount=type=cache,target=/root/.yarn YARN_CACHE_FOLDER=/root/.yarn yarn install --frozen-lockfile && yarn run build:production

FROM php:8.3-fpm-alpine
# FROM --platform=$TARGETOS/$TARGETARCH php:8.3-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apk update && apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    zip \
    unzip \
    caddy \
    #&& docker-php-ext-configure zip \
    #&& docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install bcmath gd intl zip opcache pcntl posix

# ca-certificates dcron curl git supervisor tar libxml2-dev

# Copy the Caddyfile to the container
COPY Caddyfile /etc/caddy/Caddyfile

# Copy the application code to the container
COPY . .

COPY --from=yarn /app/public/assets ./public/assets

RUN cp .env.docker .env

RUN composer install --no-dev --optimize-autoloader

# Set file permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

#RUN rm /usr/local/etc/php-fpm.conf \
#    && echo "* * * * * /usr/local/bin/php /app/artisan schedule:run >> /dev/null 2>&1" >> /var/spool/cron/crontabs/root \
#    && mkdir -p /var/run/php

EXPOSE 80
EXPOSE 443

# Start PHP-FPM
CMD ["sh", "-c", "php-fpm & caddy run --config /etc/caddy/Caddyfile --adapter caddyfile"]

ENTRYPOINT [ "/bin/ash", ".github/docker/entrypoint.sh" ]
# CMD [ "supervisord", "-n", "-c", "/etc/supervisord.conf" ]
