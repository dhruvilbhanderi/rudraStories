FROM php:8.1-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip git curl \
    libpq-dev libzip-dev libxml2-dev libonig-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip mbstring curl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY . .

RUN php artisan package:discover --ansi || true

RUN mkdir -p storage/framework/sessions \
    storage/framework/cache \
    storage/framework/views \
    storage/logs

RUN chmod -R 775 storage bootstrap/cache

ENV PORT=10000

EXPOSE 10000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t public"]
