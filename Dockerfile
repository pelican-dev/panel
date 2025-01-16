# Pelican Production Dockerfile

# ================================
# Stage 1: Build PHP Dependencies
# ================================
FROM --platform=$TARGETOS/$TARGETARCH php:8.3-fpm-alpine AS composer

WORKDIR /build

COPY . ./
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install required libraries and PHP extensions
RUN apk update && apk add --no-cache \
    libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev icu-dev \
    zip unzip curl \
    && docker-php-ext-install \
    bcmath gd intl zip opcache pcntl posix pdo_mysql

RUN composer install --no-dev --optimize-autoloader

# ================================
# Stage 2: Build Frontend Assets
# ================================
FROM --platform=$TARGETOS/$TARGETARCH node:20-alpine AS yarn

WORKDIR /build

COPY --from=composer /build .

RUN yarn config set network-timeout 300000 \
    && yarn install --frozen-lockfile \
    && yarn run build

# ================================
# Stage 3: Build Final Application Image
# ================================
FROM --platform=$TARGETOS/$TARGETARCH php:8.3-fpm-alpine

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install required libraries and PHP extensions
RUN apk update && apk add --no-cache \
    libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev icu-dev \
    zip unzip curl caddy ca-certificates supervisor \
    && docker-php-ext-install bcmath gd intl zip opcache pcntl posix pdo_mysql

COPY Caddyfile /etc/caddy/Caddyfile
COPY --from=yarn /build .

RUN touch .env

# Set permissions for Laravel directories
RUN chmod -R 755 storage bootstrap/cache \
    && chown -R www-data:www-data ./

# Add Laravel scheduler to crontab
RUN echo "* * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1" | crontab -u www-data -

# Configure Supervisor
RUN cp .github/docker/supervisord.conf /etc/supervisord.conf && \
    mkdir /var/log/supervisord/

HEALTHCHECK --interval=5m --timeout=10s --start-period=5s --retries=3 \
  CMD curl -f http://localhost/up || exit 1

EXPOSE 80 443

VOLUME /pelican-data

ENTRYPOINT [ "/bin/ash", ".github/docker/entrypoint.sh" ]
CMD [ "supervisord", "-n", "-c", "/etc/supervisord.conf" ]
