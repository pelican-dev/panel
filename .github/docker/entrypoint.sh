#!/bin/ash -e

#mkdir -p /var/log/supervisord/ /var/log/php8/ \

cd /var/www/html

#chmod -R 775 storage/* bootstrap/cache/
#chown -R caddy:caddy .

if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
  echo "Generating APP_KEY..."
  php artisan key:generate --force
else
  echo "APP_KEY is already set."
fi

## make sure the db is set up
echo -e "Migrating and Seeding Database"
php artisan migrate --force

## start cronjobs for the queue
echo -e "Starting cron jobs."
crond -L /var/log/crond -l 5

echo -e "Starting supervisord."
exec "$@"
