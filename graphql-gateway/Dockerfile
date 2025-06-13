# Gunakan image dasar PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan Laravel & GraphQL
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
    && docker-php-ext-install pdo pdo_mysql

# Aktifkan modul Apache rewrite
RUN a2enmod rewrite

# Set document root ke folder 'public'
ENV APACHE_DOCUMENT_ROOT=/var/www/public

# Ubah konfigurasi virtual host agar sesuai dengan document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Salin seluruh source code Laravel ke container
COPY . /var/www

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set permission untuk Laravel (storage, bootstrap/cache)
RUN chown -R www-data:www-data /var/www \
    && find /var/www/storage -type d -exec chmod 775 {} \; \
    && find /var/www/storage -type f -exec chmod 664 {} \; \
    && find /var/www/bootstrap/cache -type d -exec chmod 775 {} \; \
    && find /var/www/bootstrap/cache -type f -exec chmod 664 {} \;

# Set working directory ke Laravel root
WORKDIR /var/www

# Install dependency Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Expose port 80 (Apache)
EXPOSE 80
