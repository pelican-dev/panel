# Pelican Production Dockerfile

FROM node:20-alpine AS yarn
#FROM --platform=$TARGETOS/$TARGETARCH node:20-alpine AS yarn

WORKDIR /build

COPY . ./

RUN yarn install --frozen-lockfile && yarn run build:production

FROM php:8.3-fpm-alpine
# FROM --platform=$TARGETOS/$TARGETARCH php:8.3-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Install dependencies
RUN apk update && apk add --no-cache \
    libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev icu-dev \
    zip unzip curl \
    caddy ca-certificates supervisor \
    && docker-php-ext-install bcmath gd intl zip opcache pcntl posix

# Copy the Caddyfile to the container
COPY Caddyfile /etc/caddy/Caddyfile

# Copy the application code to the container
COPY . .

COPY --from=yarn /build/public/assets ./public/assets

RUN cp .env.docker .env

RUN composer install --no-dev --optimize-autoloader

# Set file permissions
RUN chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

#echo "* * * * * /usr/local/bin/php /build/artisan schedule:run >> /dev/null 2>&1" >> /var/spool/cron/crontabs/root

HEALTHCHECK --interval=5m --timeout=10s --start-period=5s --retries=3 \
  CMD curl -f http://localhost/up || exit 1

EXPOSE 80:2019
EXPOSE 443

VOLUME /pelican-data

# Start PHP-FPM
CMD ["sh", "-c", "php-fpm & caddy run --config /etc/caddy/Caddyfile --adapter caddyfile"]

ENTRYPOINT [ "/bin/ash", ".github/docker/entrypoint.sh" ]
# CMD [ "supervisord", "-n", "-c", "/etc/supervisord.conf" ]
