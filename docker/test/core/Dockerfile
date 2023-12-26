FROM 10.0.0.239:80/uvi/core:supervisord-latest

COPY . .

RUN composer install && \
    php artisan telescope:install

COPY ./docker/test/core/server.ini /etc/supervisor.d/server.ini
COPY ./docker/test/core/import.ini /etc/supervisor.d/import.ini
RUN chmod +x /var/www/artisan
