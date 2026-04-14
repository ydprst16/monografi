FROM php:8.2-apache

# Matikan SEMUA MPM dulu (biar bersih)
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true

# Aktifkan hanya prefork (wajib untuk PHP)
RUN a2enmod mpm_prefork

# Install extension MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project
COPY . /var/www/html/

# Enable rewrite
RUN a2enmod rewrite

# Fix permission (opsional tapi bagus)
RUN chown -R www-data:www-data /var/www/html