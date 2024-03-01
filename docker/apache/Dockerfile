FROM httpd:2.4.58 as prod

RUN apt update && \
    apt -y install libapache2-mod-fcgid

COPY app /var/www/html
COPY docker/apache/httpd.conf /usr/local/apache2/conf/httpd.conf

RUN chown -R www-data:www-data /var/www/html/

FROM prod as dev

RUN usermod -u 1000 www-data && \
    groupmod -g 1000 www-data
