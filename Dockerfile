FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo_mysql zip bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist

COPY . .

RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Crear directorios
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && mkdir -p /tmp/bootstrap/cache

# Permisos totales (estamos como root)
RUN chmod -R 777 storage bootstrap/cache /tmp/bootstrap

EXPOSE 8080

# NO cambiar a www-data - ejecutar como root
CMD php artisan serve --host=0.0.0.0 --port=8080