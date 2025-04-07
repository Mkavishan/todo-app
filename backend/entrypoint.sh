#!/bin/sh

# Exit immediately if any command fails
set -e

# Install compose dependencies
composer install

# Copy .env.example to .env if it doesn't exist
if [ ! -f .env ]; then
  echo "Creating .env file from .env.example"
  cp .env.example .env
fi

# Wait for MySQL to be ready
until nc -z -v -w30 db 3306
do
  echo "Waiting for MySQL database connection..."
  sleep 5
done

echo "MySQL is ready!"

# Generate application key if not set
php artisan key:generate

# Run migrations
php artisan migrate --force

# Start PHP-FPM
php-fpm
