FROM php:8.1-fpm

WORKDIR /var/www/html/

COPY .. .

RUN apt-get update && apt-get -y install git zip unzip librabbitmq-dev libpq-dev

RUN pecl install amqp redis && \
    docker-php-ext-install pdo_mysql pdo_pgsql sockets exif && \
    docker-php-ext-enable amqp redis

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup.php
RUN composer install --no-dev --optimize-autoloader --no-scripts
RUN chmod +x /var/www/html/artisan
COPY docker/prod/core/00_max_upload.ini /usr/local/etc/php/conf.d/00_max_upload.ini
COPY docker/prod/core/00_post_max_size.ini /usr/local/etc/php/conf.d/00_post_max_size.ini
COPY docker/prod/core/00_memory_limit.ini /usr/local/etc/php/conf.d/00_memory_limit.ini
COPY docker/prod/core/www.conf /usr/local/etc/php-fpm.d/www.conf
