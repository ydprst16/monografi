FROM php:8.2-apache

# Fix MPM conflict
RUN a2dismod mpm_event && a2enmod mpm_prefork

# Install MySQL extension
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project
COPY . /var/www/html/

# Enable rewrite
RUN a2enmod rewrite