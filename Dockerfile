FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libxml2-dev curl nodejs npm \
    && docker-php-ext-install pdo_mysql zip bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist
RUN php artisan key:generate --force || true
RUN php artisan storage:link || true
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true
RUN mkdir -p /tmp/bootstrap/cache /tmp/storage/framework/views && chmod -R 777 /tmp

RUN npm install
RUN npm run build -- --mode production
RUN chown -R www-data:www-data /var/www/html/public/build
RUN chmod -R 755 /var/www/html/public/build

EXPOSE 8080
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
