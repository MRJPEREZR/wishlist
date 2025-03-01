#!/bin/sh
set -e

echo "Running composer install..."
composer install --no-interaction --optimize-autoloader

echo "Waiting for vendor folder to be created..."
while [ ! -d "/var/www/vendor" ]; do
  echo "Waiting for vendor directory..."
  sleep 2
done

echo "Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

echo "Loading fixtures..."
php bin/console doctrine:fixtures:load --no-interaction

echo "Starting PHP-FPM..."
exec php-fpm
