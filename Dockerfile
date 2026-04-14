FROM php:8.2-apache

ARG CACHE_BUST=1

RUN rm -f /etc/apache2/mods-enabled/mpm_*.load
RUN a2enmod mpm_prefork

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html/

RUN a2enmod rewrite

RUN chown -R www-data:www-data /var/www/html