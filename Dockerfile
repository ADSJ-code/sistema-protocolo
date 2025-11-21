FROM php:7.4-apache

WORKDIR /var/www/html

COPY . /var/www/html/

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN a2enmod rewrite

EXPOSE 80