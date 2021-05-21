#!/bin/bash

# Exit on fail
set -e

# Composer install
composer install --no-autoloader --no-scripts --no-interaction --dev

composer dump-autoload --optimize --no-interaction

# Migrate
php artisan migrate

# Start services
php artisan serve --host=0.0.0.0

# Finally call command issued to the docker service
exec "$@"
