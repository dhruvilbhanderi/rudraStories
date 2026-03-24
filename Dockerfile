FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip git curl libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install

# Permission fix
RUN chmod -R 777 storage bootstrap/cache

# Laravel config
RUN php artisan config:clear || true
RUN php artisan cache:clear || true

CMD php artisan serve --host=0.0.0.0 --port=10000
