#!/bin/sh
set -e
php artisan migrate --force >/dev/null 2>&1 || true
cd /var/www/html/public
exec php -S "0.0.0.0:${PORT:-10000}" /var/www/html/server.php
