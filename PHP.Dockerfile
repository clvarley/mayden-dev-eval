FROM php:8.5-fpm

RUN apt-get update \ 
    && apt-get install -y default-mysql-client \
        libpng-dev \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pdo \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install sockets