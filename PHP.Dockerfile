FROM php:8.5-fpm

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN apt-get update \ 
    && apt-get install -y default-mysql-client \
        libpng-dev \
        libzip-dev \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pdo \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install sockets \
    && docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer