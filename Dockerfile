FROM php:8.1-cli

# System deps + PHP extensions needed by Laravel + Composer packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip git curl \
    libpq-dev libzip-dev libxml2-dev libonig-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip mbstring curl \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

# Copy only composer files first (better caching)
COPY composer.json composer.lock ./

# Install PHP deps (avoid running Laravel scripts during image build)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copy the rest of the app
COPY . .

# Package discovery is helpful, but don't fail the build if env isn't ready yet
RUN php artisan package:discover --ansi || true

# Permissions (Laravel needs these writable)
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000
CMD ["sh", "-lc", "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
