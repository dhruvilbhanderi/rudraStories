FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# ✅ Copy only composer files first (better caching)
COPY composer.json composer.lock ./

# ✅ Install dependencies FIRST
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# ✅ Then copy rest of the project
COPY . .

# ✅ Run Laravel setup AFTER vendor exists
RUN php artisan package:discover --ansi || true

# Permissions
RUN chmod -R 775 storage bootstrap/cache

# Clear cache
RUN php artisan config:clear || true
RUN php artisan cache:clear || true

EXPOSE 10000

CMD ["sh", "-lc", "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
