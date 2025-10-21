# ===========================
# Dockerfile para Laravel 12
# ===========================

# 1. Usamos PHP 8.2 con FPM
FROM php:8.2-fpm


# 2. Instala dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip bcmath \
    && docker-php-ext-enable bcmath


# 3. Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Establece el directorio de trabajo
WORKDIR /var/www/html

# 5. Copia archivos del proyecto
COPY . .

# 6. Instala dependencias de Laravel
RUN composer install --optimize-autoloader --no-dev --no-interaction


# 8. Genera key de Laravel
RUN php artisan key:generate

# 9. Ajusta permisos (Linux)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Expone el puerto 8080 (Railway usa este puerto)
EXPOSE 8080

# 11. Comando para ejecutar Laravel
CMD php artisan serve --host=0.0.0.0 --port=8080
