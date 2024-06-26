#!/bin/ash -e

#mkdir -p /var/log/supervisord/ /var/log/php8/ \

## Make sure the app key is set
php artisan key:generate

## make sure the db is set up
echo -e "Migrating and Seeding Database"
touch database/database.sqlite
php artisan migrate --force

## start cronjobs for the queue
echo -e "Starting cron jobs."
crond -L /var/log/crond -l 5

#chmod -R 755 storage/* bootstrap/cache/
chown -R www-data:www-data .

echo -e "Starting supervisord."
exec "$@"
