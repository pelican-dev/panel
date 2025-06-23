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

export SUPERVISORD_CADDY=false

## disable caddy if SKIP_CADDY is set
if [[ "${SKIP_CADDY:-}" == "true" ]]; then
  echo "Starting PHP-FPM only"
else
  echo "Starting PHP-FPM and Caddy"
  export SUPERVISORD_CADDY=true
fi

echo "Starting Supervisord"
exec "$@"
