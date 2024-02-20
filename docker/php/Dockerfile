FROM php:7.4.33-fpm

LABEL authors="Mauro Chojrin <mauro.chojrin@leewayweb.com>"

RUN apt-get update && \
    apt-get install -y \
        libxml2-dev

RUN docker-php-ext-install mysqli xml

RUN usermod -u 1000 www-data && \
    groupmod -g 1000 www-data