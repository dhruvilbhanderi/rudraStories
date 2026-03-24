FROM php:8.2-cli

# Dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chmod -R 777 storage bootstrap/cache

# Clear cache
RUN php artisan config:clear || true
RUN php artisan cache:clear || true

CMD php artisan serve --host=0.0.0.0 --port=10000
