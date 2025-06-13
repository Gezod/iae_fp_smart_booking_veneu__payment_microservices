#!/bin/bash
set -e

# Cek apakah artisan ada
if [ ! -f /var/www/artisan ]; then
  echo "Laravel artisan file not found!"
  exit 1
fi

echo "Generating app key if needed..."
php /var/www/artisan key:generate --force

echo "Clearing Laravel caches..."
php /var/www/artisan config:clear
php /var/www/artisan cache:clear
php /var/www/artisan route:clear
php /var/www/artisan view:clear

echo "Running database migrations..."
php /var/www/artisan migrate --force

echo "Setting proper permissions..."
chown -R www-data:www-data /var/www
chmod -R 755 /var/www/storage /var/www/bootstrap/cache

echo "Starting Apache server..."
exec apache2-foreground
