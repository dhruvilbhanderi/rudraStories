FROM php:8.2-cli

# Dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl \
    libpq-dev libzip-dev libxml2-dev libonig-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring xml dom curl

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app
COPY . .

# Install Laravel dependencies (avoid artisan scripts during image build)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Attempt package discovery (non-fatal if env vars aren't present at build time)
RUN php artisan package:discover --ansi || true

# Permissions
RUN chmod -R 777 storage bootstrap/cache

# Clear cache
RUN php artisan config:clear || true
RUN php artisan cache:clear || true

# Render listens on $PORT (default 10000 here)
EXPOSE 10000

# Render sets $PORT at runtime
CMD ["sh", "-lc", "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
