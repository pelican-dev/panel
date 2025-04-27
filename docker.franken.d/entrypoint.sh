#!/bin/ash -e
# shellcheck shell=dash

# Set up environment variables for consistent configuration
export PELICAN_HOME=${PELICAN_HOME:-"/pelican"}
export PELICAN_APP=${PELICAN_APP:-"$PELICAN_HOME/app"}
export PELICAN_CONFIG=${PELICAN_CONFIG:-"$PELICAN_HOME/config"}
export PELICAN_DATA=${PELICAN_DATA:-"$PELICAN_HOME/data"}

# Set Caddy environment variables
export XDG_DATA_HOME="$PELICAN_DATA"
export XDG_CONFIG_HOME="$PELICAN_CONFIG"

# Default values for PUID/PGID if not provided
PUID=${PUID:-1000}
PGID=${PGID:-1000}

echo "Starting with UID: $PUID, GID: $PGID"

# Function to check and update user/group IDs if needed
check_user() {
    # Update the abc user and group with the provided IDs
    if [ "$PUID" != "1000" ] || [ "$PGID" != "1000" ]; then
        echo "Updating user and group IDs..."

        # Delete and recreate the user and group with new IDs
        deluser abc
        addgroup -g "$PGID" abc
        adduser -D -u "$PUID" -G abc -h "$PELICAN_HOME" abc

        # Update file ownership efficiently, excluding vendor directory
        echo "Updating file ownership (this may take a while)..."
        # Update app directory excluding vendor
        echo "Updating app directory (excluding vendor)..."
        find "$PELICAN_APP" -path "$PELICAN_APP/vendor" -prune -o -exec chown abc:abc {} \+ 2>/dev/null || true

        # Update config and data directories
        echo "Updating config and data directories..."
        chown -R abc:abc "$PELICAN_CONFIG" "$PELICAN_DATA" 2>/dev/null || true

        echo "UID/GID update completed"
    fi
}

# Function to initialize the application
initialize_app() {
    # Check if a custom Caddyfile exists in the config volume
    CADDYFILE="$PELICAN_CONFIG/Caddyfile"
    if [ ! -f "$CADDYFILE" ]; then
        echo "No custom Caddyfile found in config volume, copying default Caddyfile"
        cp "$PELICAN_APP/Caddyfile.template" "$CADDYFILE"
        # No need to change ownership as we're already running as abc user
    else
        echo "Using custom Caddyfile from config volume"
    fi

    # Handle .env file
    ENVFILE="$PELICAN_CONFIG/.env"
    if [ ! -f "$ENVFILE" ]; then
        echo "Creating new .env file from .env.example"
        cp "$PELICAN_APP/.env.example" "$ENVFILE"

        # Ensure proper permissions on the new file
        if [ "$(id -u)" != "0" ]; then
            # We're running as non-root, so make sure the file is group-writable
            chmod g+rw "$ENVFILE"
        fi
    fi

    # Check if APP_KEY needs to be generated
    if ! grep -q "^APP_KEY=.\+" "$ENVFILE"; then
        echo "No APP_KEY found, generating one..."
        php artisan key:generate
    else
        echo "APP_KEY is already set in .env file"
    fi

    # Ensure database directory exists and has proper permissions
    if [ ! -d "$PELICAN_DATA/database" ]; then
        echo "Creating database directory"
        mkdir -p "$PELICAN_DATA/database"

        # Ensure proper permissions on the new directory
        if [ "$(id -u)" != "0" ]; then
            # We're running as non-root, so make sure the directory is group-writable
            chmod g+rwx "$PELICAN_DATA/database"
        fi
    fi

    # Make sure the db is set up
    echo "Migrating Database"
    cd "$PELICAN_APP"
    php artisan migrate --force

    echo "Optimizing Filament"
    php artisan filament:optimize

    # Set default admin credentials if not provided
    ADMIN_EMAIL=${ADMIN_EMAIL:-"pelican@example.com"}
    ADMIN_USERNAME=${ADMIN_USERNAME:-"pelican"}
    ADMIN_PASSWORD=${ADMIN_PASSWORD:-"pelican"}

    # Create default admin user if not already created
    ADMIN_FLAG_FILE="$PELICAN_CONFIG/.admin_user_created"

    if [ ! -f "$ADMIN_FLAG_FILE" ] && [ -n "$ADMIN_EMAIL" ] && [ -n "$ADMIN_PASSWORD" ]; then
        echo "Creating initial admin user..."
        if php artisan p:user:make --email="${ADMIN_EMAIL}" --username="${ADMIN_USERNAME}" --password="${ADMIN_PASSWORD}" --admin=yes; then
            echo "Admin user created successfully."
            # Create flag file to indicate admin user has been created
            touch "$ADMIN_FLAG_FILE"
            echo "$(date): Initial admin user created with username '${ADMIN_USERNAME}' and email '${ADMIN_EMAIL}'" > "$ADMIN_FLAG_FILE"
        else
            echo "Failed to create admin user."
        fi
    else
        if [ -f "$ADMIN_FLAG_FILE" ]; then
            echo "Admin user was already created previously. To create a new admin user, delete the file: $ADMIN_FLAG_FILE"
        fi
    fi

    # Setup Laravel queue worker in background
    if [ "${ENABLE_QUEUE_WORKER:-}" = "true" ]; then
        echo "Starting Laravel queue worker"
        php artisan queue:work --tries=3 &
    fi

    # Setup Laravel scheduler using supercronic in background
    if [ "${ENABLE_SCHEDULER:-}" = "true" ]; then
        echo "Starting Laravel scheduler"
        supercronic -overlapping /etc/supercronic/crontab &
    fi
}

# Main script execution
if [ "$(id -u)" = "0" ]; then
    # Check and update user/group IDs if needed
    check_user

    # Switch to abc user to run the rest of the script
    echo "Switching to abc user for the rest of the script"
    cd "$PELICAN_APP"
    exec su-exec abc:abc "$0" "$@"
else
    # Already running as non-root user, just initialize the app
    initialize_app

    # Starting FrankenPHP with both HTTP and HTTPS support
    echo "Starting FrankenPHP with both HTTP and HTTPS support (ports 80 and 443)"

    # Execute the command
    exec "$@"
fi
