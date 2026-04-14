FROM php:8.2-apache

ARG CACHE_BUST=1

# Disable all MPMs first, then enable only prefork
RUN a2dismod mpm_event mpm_worker mpm_prefork 2>/dev/null || true \
    && a2enmod mpm_prefork

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html/

RUN a2enmod rewrite

RUN chown -R www-data:www-data /var/www/html