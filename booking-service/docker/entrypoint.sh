#!/bin/bash
set -e

# Cek apakah artisan ada
if [ ! -f /var/www/artisan ]; then
  echo "Laravel artisan file not found!"
  exit 1
fi

echo "Running Composer update..."
composer update --no-dev --optimize-autoloader

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

# 🔁 Tambahkan Worker RabbitMQ untuk Booking Event
# echo "Starting Laravel queue worker (RabbitMQ)..."
# php /var/www/artisan queue:work --tries=3 --timeout=30 &

echo "Starting Apache server..."
exec apache2-foreground
