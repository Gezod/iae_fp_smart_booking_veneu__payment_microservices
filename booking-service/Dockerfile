# Gunakan PHP + Apache base image
FROM php:8.2-apache

# Install ekstensi PHP yang diperlukan Laravel, termasuk sockets
RUN apt-get update && apt-get install -y \
    git unzip zip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath sockets

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory Laravel
WORKDIR /var/www

# Salin source code Laravel ke container
COPY . .

# Install dependensi Laravel (no dev, optimize autoloader)
RUN composer install --no-dev --optimize-autoloader

# Salin konfigurasi Apache (pastikan file ini ada di folder docker)
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Aktifkan mod_rewrite Apache yang diperlukan Laravel
RUN a2enmod rewrite

# Set permission storage dan cache folder (sementara, nanti akan diperbaiki di entrypoint)
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Salin entrypoint script dan beri permission execute
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port 80 untuk Apache
EXPOSE 80

# Gunakan entrypoint.sh saat container start
ENTRYPOINT ["entrypoint.sh"]
