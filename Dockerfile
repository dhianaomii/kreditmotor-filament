FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    nodejs \
    npm \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm ci && npm run build

RUN chmod -R 775 storage bootstrap/cache

# Startup script - baca $PORT dari Railway
RUN echo '#!/bin/bash\n\
php-fpm -D\n\
sleep 2\n\
sed -i "s/RAILWAY_PORT/$PORT/g" /etc/nginx/sites-enabled/default\n\
nginx -g "daemon off;"' > /start.sh && chmod +x /start.sh

RUN echo 'server {\n\
    listen RAILWAY_PORT;\n\
    root /app/public;\n\
    index index.php;\n\
    location / { try_files $uri $uri/ /index.php?$query_string; }\n\
    location ~ \\.php$ {\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;\n\
        include fastcgi_params;\n\
    }\n\
}' > /etc/nginx/sites-enabled/default

EXPOSE 8080

CMD ["/bin/bash", "/start.sh"]