# Pelican Production Dockerfile

FROM php:8.3-fpm-alpine
# FROM --platform=$TARGETOS/$TARGETARCH php:8.3-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

RUN touch .env

# Install dependencies
RUN apk update && apk add --no-cache \
    libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev icu-dev \
    zip unzip curl \
    caddy ca-certificates supervisor \
    && docker-php-ext-install bcmath gd intl zip opcache pcntl posix pdo_mysql

# Install dependencies with Composer
COPY composer.json composer.lock ./
COPY app/helpers.php ./app/

RUN composer install --no-dev --optimize-autoloader

# Install dependencies with Composer
COPY package.json yarn.lock ./
RUN apk add --no-cache yarn
RUN yarn config set network-timeout 300000 \
    && yarn install --frozen-lockfile

# Copy the application code to the container
COPY . .

# Yarn build
RUN yarn run build

# Set file permissions
RUN chmod -R 755 storage bootstrap/cache \
    && chown -R www-data:www-data ./

# Add scheduler to cron
RUN echo "* * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1" | crontab -u www-data -

# Copy the Caddyfile to the container
COPY Caddyfile /etc/caddy/Caddyfile

## supervisord config and log dir
RUN cp .github/docker/supervisord.conf /etc/supervisord.conf && \
    mkdir /var/log/supervisord/

HEALTHCHECK --interval=5m --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

EXPOSE 80 443

VOLUME /pelican-data

ENTRYPOINT [ "/bin/ash", ".github/docker/entrypoint.sh" ]
CMD [ "supervisord", "-n", "-c", "/etc/supervisord.conf" ]
