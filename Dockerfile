# ===========================
# Dockerfile para Laravel 12
# ===========================

FROM php:8.2-fpm

# Instala dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install pdo_mysql zip bcmath opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia composer files primero
COPY composer.json composer.lock ./

# Instala dependencias (bcmath ya está disponible aquí)
RUN composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist

# Copia el resto de archivos
COPY . .

# Genera key de Laravel
RUN php artisan key:generate --force || true

# Storage link
RUN php artisan storage:link || true

# Cachea configuraciones
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# Ajusta permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expone el puerto
EXPOSE 8080

# Comando de inicio
CMD php artisan serve --host=0.0.0.0 --port=8080