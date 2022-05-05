FROM php:7.4-fpm

WORKDIR /go-server

RUN apt-get update && apt-get install -y libzip-dev

# Extension zip for laravel
RUN docker-php-ext-install zip 

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer global require laravel/installer
RUN docker-php-ext-install pdo_mysql mysqli

ENTRYPOINT ["sh", "-c", "composer install && php artisan server"]