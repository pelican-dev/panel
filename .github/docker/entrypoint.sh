#!/bin/ash -e

#mkdir -p /var/log/supervisord/ /var/log/php8/ \

## check for .env file and generate app keys if missing
if [ -f /pelican-data/.env ]; then
  echo "external vars exist."
  rm -rf /var/www/html/.env
else
  echo "external vars don't exist."
  rm -rf /var/www/html/.env
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

mkdir /pelican-data/database
ln -s /pelican-data/.env /var/www/html/
ln -s /pelican-data/database/database.sqlite /var/www/html/database/

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

## start cronjobs for the queue
echo -e "Starting cron jobs."
crond -L /var/log/crond -l 5

export SUPERVISORD_CADDY=false

## disable caddy if SKIP_CADDY is set
if [[ -z $SKIP_CADDY ]]; then
  echo "Starting PHP-FPM and Caddy"
  export SUPERVISORD_CADDY=true
else
  echo "Starting PHP-FPM only"
fi

chown -R www-data:www-data . /pelican-data/.env /pelican-data/database

echo "Starting Supervisord"
exec "$@"
