FROM php:8.2-apache

# HAPUS semua MPM yang aktif
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load

# Aktifkan hanya prefork
RUN a2enmod mpm_prefork

# Install ekstensi MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project
COPY . /var/www/html/

# Enable rewrite
RUN a2enmod rewrite

# Pastikan permission aman
RUN chown -R www-data:www-data /var/www/html