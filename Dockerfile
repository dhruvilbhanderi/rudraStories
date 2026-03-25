FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl \
    libpq-dev libzip-dev libxml2-dev libonig-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring xml dom curl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

# ✅ Copy only composer files first (better caching)
COPY composer.json composer.lock ./

# ✅ Install dependencies FIRST
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# ✅ Then copy rest of the project
COPY . .

<<<<<<< HEAD
# Install Laravel dependencies (avoid artisan scripts during image build)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Attempt package discovery (non-fatal if env vars aren't present at build time)
=======
# ✅ Run Laravel setup AFTER vendor exists
>>>>>>> 45f8735b54d43f64b6531e3e41bfa7634e1a08a8
RUN php artisan package:discover --ansi || true

# Permissions
RUN chmod -R 775 storage bootstrap/cache

# Clear cache
RUN php artisan config:clear || true
RUN php artisan cache:clear || true

EXPOSE 10000

CMD ["sh", "-lc", "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
