FROM php:8.2-fpm

ARG user
ARG uid

RUN apt-get update && apt-get install -y \
    git curl \
    libpng-dev libonig-dev libxml2-dev \
    libjpeg-dev libfreetype6-dev libjpeg62-turbo-dev \
    libwebp-dev libzip-dev zip unzip \
    jpegoptim optipng pngquant gifsicle webp \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j$(nproc) \
    pdo_mysql mbstring exif pcntl bcmath gd zip opcache

RUN pecl install redis && docker-php-ext-enable redis

COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user \
 && mkdir -p /home/$user/.composer \
 && chown -R $user:$user /home/$user

WORKDIR /var/www
USER $user
