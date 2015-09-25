FROM php:5.4-apache

ENV ADT_DEV_MODE true

RUN pecl install apcu-beta \
    && echo extension=apcu.so > /usr/local/etc/php/conf.d/apcu.ini

RUN mkdir -p /conf/adt/
# Copy samples files to have datas to process in the UI
COPY resources/*.org /conf/adt/

WORKDIR /var/adt
VOLUME ["/var/adt"]

CMD php -S 0.0.0.0:80 -c /var/adt/etc/php/cli-server.ini -t /var/adt/var/www/ /var/adt/var/www/router.php
