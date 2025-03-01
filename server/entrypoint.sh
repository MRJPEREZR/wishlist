#!/bin/sh
set -e

echo "Running composer install..."
composer install --no-scripts --no-autoloader --no-interaction --optimize-autoloader

# Wait for the database to be ready before running migrations
echo "Waiting for database connection..."
until nc -z -v -w30 db 3306; do
  echo "Waiting for MySQL..."
  sleep 5
done

echo "Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

echo "Loading fixtures..."
php bin/console doctrine:fixtures:load --no-interaction

echo "Starting PHP-FPM..."
exec php-fpm
