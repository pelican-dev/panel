#!/bin/ash -e

/usr/local/bin/php /var/www/html/artisan queue:work --tries=5
