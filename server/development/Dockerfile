FROM php:7.4-fpm
# ENV APP_ENV=dev

WORKDIR /go-server

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt-get update && apt-get install -y libzip-dev

# Extension zip for laravel
RUN docker-php-ext-install zip 

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo_mysql mysqli
RUN composer global require laravel/installer

RUN apt-get clean
COPY . .
RUN cp .env.example .env

EXPOSE 8000

ENTRYPOINT [ "sh", "-c", "composer install && php artisan serve --port=8000 --host=0.0.0.0"]