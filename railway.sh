#!/bin/bash

# Clear all caches first
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Cache config
php artisan config:cache

# Start the server
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
