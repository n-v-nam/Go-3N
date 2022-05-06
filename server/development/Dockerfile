FROM php:7.4-apache
# ENV APP_ENV=dev

WORKDIR /go-server

RUN apt-get update && apt-get install -y libzip-dev

# Extension zip for laravel
RUN docker-php-ext-install zip 

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo_mysql mysqli
RUN composer global require laravel/installer

COPY . .

# RUN composer install 
#RUN php artisan migrate

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port=8000"]