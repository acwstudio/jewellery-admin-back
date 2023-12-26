FROM 10.0.0.239:80/uvi/core:latest

COPY . .

RUN apk add php81-pecl-redis
RUN composer install --no-dev --no-scripts

RUN chmod +x /var/www/artisan
