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

# Cached manifests from dev (Boost, Breeze, etc.) must not ship with --no-dev vendor.
RUN rm -f bootstrap/cache/packages.php bootstrap/cache/services.php \
    && APP_KEY="base64:$(openssl rand -base64 32 | tr -d '\n\r')" php artisan package:discover --ansi --no-interaction

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Defaults when Render env omits these: avoid DB on every request until Postgres is wired.
# Override in the dashboard with database drivers once DB_URL is set and migrated.
ENV SESSION_DRIVER=file \
    CACHE_STORE=file \
    QUEUE_CONNECTION=sync \
    LOG_CHANNEL=stderr

ENV PORT=10000
EXPOSE 10000

# php artisan serve uses Laravel's server.php which logs every request to php://stdout
# before index.php — that can cause "headers already sent" on Render. Use php -S + quiet server.php.
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
