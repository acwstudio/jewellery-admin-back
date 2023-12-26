FROM 10.0.0.239:80/uvi/core:supervisord-latest

RUN apk add --no-cache php81-pecl-xdebug

RUN git config --global --add safe.directory /var/www

RUN apk add --no-cache php81-sockets \
    php81-pecl-amqp

COPY xdebug.ini /etc/php81/conf.d/xdebug.ini
COPY server.ini /etc/supervisor.d/server.ini

RUN addgroup -g 1000 sail \
    && adduser -G sail -u 1000 --disabled-password sail

