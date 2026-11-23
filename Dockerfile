FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql \
    && sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html/
