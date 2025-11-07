#!/bin/ash -e
## check for .env file or symlink and generate app keys if missing
if [ -f /var/www/html/.env ]; then
  echo "external vars exist."
else
  echo "external vars don't exist."
  # webroot .env is symlinked to this path
  touch /pelican-data/.env

  ## manually generate a key because key generate --force fails
  if [ -z $APP_KEY ]; then
     echo -e "Generating key."
     APP_KEY=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
     echo -e "Generated app key: $APP_KEY"
     echo -e "APP_KEY=$APP_KEY" > /pelican-data/.env
  else
    echo -e "APP_KEY exists in environment, using that."
    echo -e "APP_KEY=$APP_KEY" > /pelican-data/.env
  fi

  ## enable installer
  echo -e "APP_INSTALLED=false" >> /pelican-data/.env
fi

## Configure PHP settings
UPLOAD_LIMIT_VAL=${UPLOAD_LIMIT:-100}

# Calculate max_execution_time based on upload limit (larger files need more time)
# Base time: 300s, additional 3s per MB above 100MB
if [ "$UPLOAD_LIMIT_VAL" -gt 100 ]; then
  CALCULATED_EXECUTION_TIME=$((300 + (UPLOAD_LIMIT_VAL - 100) * 3))
else
  CALCULATED_EXECUTION_TIME=300
fi

sed -i "s/^upload_max_filesize = .*/upload_max_filesize = ${UPLOAD_LIMIT_VAL}M/" /usr/local/etc/php/php.ini-production
sed -i "s/^post_max_size = .*/post_max_size = ${UPLOAD_LIMIT_VAL}M/" /usr/local/etc/php/php.ini-production
sed -i "s/^max_execution_time = .*/max_execution_time = ${PHP_MAX_EXECUTION_TIME:-$CALCULATED_EXECUTION_TIME}/" /usr/local/etc/php/php.ini-production
sed -i "s/^memory_limit = .*/memory_limit = ${PHP_MEMORY_LIMIT:-512M}/" /usr/local/etc/php/php.ini-production
mkdir -p /pelican-data/database /pelican-data/storage/avatars /pelican-data/storage/fonts /var/www/html/storage/logs/supervisord 2>/dev/null

if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
  echo "Generating APP_KEY..."
  php artisan key:generate --force
else
  echo "APP_KEY is already set."
fi

## make sure the db is set up
echo -e "Migrating Database"
php artisan migrate --force

echo -e "Optimizing Filament"
php artisan filament:optimize

# default to caddy not starting
export SUPERVISORD_CADDY=false

## Configure webserver based on environment variables
if [ "${SKIP_WEBSERVER:-}" = "true" ]; then
  echo "Starting PHP-FPM only (external webserver mode)"
  # Only PHP-FPM will run, no internal webserver
elif [ "${SKIP_CADDY:-}" = "true" ]; then
  echo "Starting PHP-FPM only (Caddy disabled)"
  # Only PHP-FPM will run, but for internal use without webserver
else
  echo "Starting PHP-FPM and Caddy webserver"
  # enable caddy
  export SUPERVISORD_CADDY=true

  # handle trusted proxies for caddy
  if [ -n "${TRUSTED_PROXIES:-}" ]; then
    CADDY_TRUSTED_PROXIES_VALUE=$(echo "trusted_proxies static ${TRUSTED_PROXIES}" | sed 's/,/ /g')
    export CADDY_TRUSTED_PROXIES="$CADDY_TRUSTED_PROXIES_VALUE"
    export CADDY_STRICT_PROXIES="trusted_proxies_strict"
  fi
fi

echo "Starting Supervisord"
exec "$@"
