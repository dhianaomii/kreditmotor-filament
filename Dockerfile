FROM php:8.1-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    build-essential \
    libmcrypt-dev \
    mariadb-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    locales \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    zip \
    libicu-dev \
    nginx \
    nodejs \
    npm \
    && apt-get clean && rm -rf /var/lib/apt/list/*

RUN docker-php-ext-install pdo pdo_mysql gd zip intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/sites-enabled/default
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/bin/bash", "/start.sh"]