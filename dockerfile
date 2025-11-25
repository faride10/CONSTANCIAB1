FROM composer:2.7.7 as composer

FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    git \
    zip \
    libzip-dev \
    libpng-dev \
    icu-dev \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    && docker-php-ext-install pdo_mysql opcache 

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . /app

RUN composer install --no-dev --optimize-autoloader

RUN apk add --no-cache nodejs npm
RUN npm install
RUN npm run build

RUN php artisan key:generate \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && chmod -R 777 storage bootstrap/cache

CMD ["php-fpm"]