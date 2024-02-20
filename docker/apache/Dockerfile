FROM httpd:2.4.58

RUN apt update && \
    apt -y install libapache2-mod-fcgid

RUN usermod -u 1000 www-data && \
    groupmod -g 1000 www-data