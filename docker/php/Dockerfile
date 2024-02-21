FROM php:7.4.33-fpm as prod

LABEL authors="Mauro Chojrin <mauro.chojrin@leewayweb.com>"

RUN apt-get update && \
    apt-get install -y \
        libxml2-dev

RUN docker-php-ext-install mysqli xml

COPY app /var/www/html
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

FROM prod as dev

RUN usermod -u 1000 www-data && \
    groupmod -g 1000 www-data

RUN pecl install xdebug-3.1.6 && \
    docker-php-ext-enable xdebug