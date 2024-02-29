FROM php:8.3-fpm

RUN apt-get update && apt-get install -y composer

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer install

EXPOSE 8000

CMD ["php", "artisan", "serve"]
