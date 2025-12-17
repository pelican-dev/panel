#!/bin/ash -e
# check for .env file or symlink and generate app keys if missing
if [ -f /var/www/html/.env ]; then
  echo "external vars exist."
  # load env vars from .env
  export $(grep -v '^#' .env | xargs)
else
  echo "external vars don't exist."
  # webroot .env is symlinked to this path
  touch /pelican-data/.env

  # manually generate a key because key generate --force fails
  if [ -z ${APP_KEY} ]; then
    echo -e "Generating key."
    APP_KEY=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
    echo -e "Generated app key: $APP_KEY"
    echo -e "APP_KEY=$APP_KEY" > /pelican-data/.env
  else
    echo -e "APP_KEY exists in environment, using that."
    echo -e "APP_KEY=$APP_KEY" > /pelican-data/.env
  fi

  # enable installer
  echo -e "APP_INSTALLED=false" >> /pelican-data/.env
fi

# create directories for volumes
mkdir -p /pelican-data/database /pelican-data/storage/avatars /pelican-data/storage/fonts /var/www/html/storage/logs/supervisord 2>/dev/null

# if the app is installed then we need to run migrations on start. New installs will run migrations when you run the installer.
if [ "${APP_INSTALLED}" == "true" ];  then
  #if the db is anything but sqlite wait until it's accepting connections
  if [ "${DB_CONNECTION}" != "sqlite" ]; then
    # check for DB up before starting the panel
    echo "Checking database status."
    until nc -z -v -w30 $DB_HOST $DB_PORT
    do
      echo "Waiting for database connection..."
      # wait for 1 seconds before check again
      sleep 1
    done
  fi
  # run migration
  php artisan migrate --force
fi

echo -e "Optimizing Filament"
php artisan filament:optimize

# default to caddy not starting
export SUPERVISORD_CADDY=false
export PARSED_APP_URL=${APP_URL}

# checking if app url is using https
if echo "${APP_URL}" | grep -qE '^https://'; then
  echo "https domain found setting email var"
  export PARSED_LE_EMAIL="email ${LE_EMAIL}"
fi

# when running behind a proxy
if [ "${BEHIND_PROXY}" == "true" ]; then
  echo "running behind proxy"
  echo "listening on port 80 internally"
  export PARSED_LE_EMAIL=""
  export PARSED_APP_URL=":80"
  export PARSED_AUTO_HTTPS="auto_https off"
  export ASSET_URL=${APP_URL}
fi

# disable caddy if SKIP_CADDY is set
if [ "${SKIP_CADDY:-}" == "true" ]; then
  echo "Starting PHP-FPM only"
else
  echo "Starting PHP-FPM and Caddy"
  # enable caddy
  export SUPERVISORD_CADDY=true

  # handle trusted proxies for caddy when variable has data
  if [ ! -z ${TRUSTED_PROXIES} ]; then
    export CADDY_TRUSTED_PROXIES=$(echo "trusted_proxies static ${TRUSTED_PROXIES}" | sed 's/,/ /g')
    export CADDY_STRICT_PROXIES="trusted_proxies_strict"
  fi
fi

echo "Starting Supervisord"
exec "$@"