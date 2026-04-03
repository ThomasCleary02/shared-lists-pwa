# Multi-stage build for Render (PHP 8.4 + Laravel + Vite assets).
# Composer runs first: Vite imports Ziggy from vendor/tightenco/ziggy, which
# .dockerignore strips from the build context, so we copy it from this stage.
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist
COPY . .
RUN composer dump-autoload --optimize

FROM node:22-bookworm AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
COPY --from=vendor /app/vendor/tightenco /app/vendor/tightenco
RUN npm run build

FROM php:8.4-cli-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

ENV PORT=10000
EXPOSE 10000

CMD sh -c "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"
