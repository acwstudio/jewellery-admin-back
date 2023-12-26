FROM 10.0.0.239:80/uvi/core:latest

ARG WWWGROUP

RUN apk add --no-cache php81-pecl-xdebug
COPY xdebug.ini /etc/php81/conf.d/xdebug.ini

RUN git config --global --add safe.directory /var/www

RUN addgroup -g ${WWWGROUP} sail \
    && adduser -G sail -u ${WWWGROUP} --disabled-password sail \
    && sed -i "s/memory_limit = 128M/memory_limit = 512M/g" /etc/php81/php.ini

