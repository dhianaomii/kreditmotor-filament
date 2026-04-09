#!/bin/bash
php artisan config:cache
php artisan migrate --force
php artisan db:seed --class=UserSeeder --force
php-fpm -D
sleep 2
sed -i "s/RAILWAY_PORT/$PORT/g" /etc/nginx/sites-enabled/default
nginx -g "daemon off;"