FROM ubuntu:23.04

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update

# Install useful tools
RUN apt-get -y install apt-utils nano wget dialog vim

# Install important libraries
RUN apt-get -y install --fix-missing \
    php8.1 \
    php8.1-fpm \
    php8.1-pgsql \
    php8.1-mbstring \
    unzip \
    git \
    php8.1-curl \
    zlib1g-dev \
    libpng-dev \
    libxml2 \
    libxml2-dev \
    libzip-dev \
    php8.1-gd \
    --no-install-recommends libssl-dev\
    php8.1-zip\
    php-xml\
    php8.1-soap\
    php8.1-sqlite3\
    php8.1-xdebug

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

ARG MOUNT_PATH

COPY . /var/www/netflix

WORKDIR /var/www/netflix

RUN composer install

RUN php artisan key:generate

CMD php artisan migrate ;\
    php artisan db:seed;\
    php artisan serve --host=0.0.0.0
