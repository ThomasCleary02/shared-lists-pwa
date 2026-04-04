#!/bin/sh
set -e
cd /var/www/html
php artisan migrate --force >/dev/null 2>&1 || true

PORT="${PORT:-10000}"

# Render injects PORT; nginx must match. php -S (cli-server) + Symfony responses
# can still hit "headers already sent" / nested exception rendering — use FPM.
cat >/etc/nginx/conf.d/default.conf <<EOF
server {
    listen ${PORT};
    server_name _;
    root /var/www/html/public;
    index index.php;
    client_max_body_size 25M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        try_files \$uri =404;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_hide_header X-Powered-By;
    }
}
EOF

php-fpm --daemonize
exec nginx -g 'daemon off;'
