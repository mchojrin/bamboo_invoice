FROM httpd:2.4.58

RUN apt update && \
    apt -y install libapache2-mod-fcgid

COPY docker/apache/my-httpd.conf /usr/local/apache2/conf/httpd.conf

RUN usermod -u 1000 www-data && \
    groupmod -g 1000 www-data