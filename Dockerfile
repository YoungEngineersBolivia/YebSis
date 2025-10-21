FROM php:8.2-fpm

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo_mysql zip bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Instalar dependencias primero (mejor cache)
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist

# Copiar el código
COPY . .

# Configuración inicial
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Crear directorios necesarios
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache

# Permisos correctos
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# Ejecutar como www-data
USER www-data

# Comando de inicio
CMD php artisan serve --host=0.0.0.0 --port=8080