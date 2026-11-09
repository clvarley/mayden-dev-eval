FROM php:8.5-fpm

RUN apt-get update \ 
    && apt-get install -y default-mysql-client \
        libpng-dev \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install gd \
    && docker-php-ext-install sockets