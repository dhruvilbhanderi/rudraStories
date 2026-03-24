FROM php:8.2-cli

# System dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Composer install
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install

# Laravel setup
RUN php artisan key:generate || true

CMD php artisan serve --host=0.0.0.0 --port=10000
