FROM php:8.2-fpm
WORKDIR /var/www/html/
RUN apt-get update && apt-get -y install git zip unzip librabbitmq-dev libpq-dev
RUN pecl install amqp redis && \
    docker-php-ext-install pdo_mysql pdo_pgsql sockets exif && \
    docker-php-ext-enable amqp redis
COPY build_conf/php-fpm-config/ /usr/local/etc-php-fpm.d/
COPY . /var/www/html/
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"
RUN composer install
CMD php artisan serve --host 0.0.0.0 --port 8000
