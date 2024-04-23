#!/bin/bash

(crontab -l ; echo "* * * * * php /var/www/pelican/artisan schedule:run >> /dev/null 2>&1") | crontab -

echo "Cronjob has been added!"
