FROM 10.0.0.239:80/uvi/core:supervisord-latest

COPY . .

RUN apk add php81-pecl-redis
RUN composer install --no-dev --no-scripts

COPY ./docker/prod/import/import.ini /etc/supervisor.d/import.ini
RUN chmod +x /var/www/artisan
