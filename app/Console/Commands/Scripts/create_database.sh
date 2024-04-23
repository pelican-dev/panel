#!/bin/bash

source /var/www/pelican/database.txt

mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD -e "CREATE DATABASE IF NOT EXISTS panel;"

mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD -e "CREATE USER 'pelican'@'$DB_USERIP' IDENTIFIED BY '$DB_USERPASSWORD';"
mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD -e "GRANT ALL PRIVILEGES ON panel.* TO 'pelican'@'$DB_USERIP';"
