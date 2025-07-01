FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones de PHP necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git unzip zip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    wkhtmltopdf libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd dom xml

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el código fuente de Laravel al contenedor
WORKDIR /var/www/html
COPY . /var/www/html

# Ajustar Apache para servir desde public y habilitar .htaccess
RUN a2enmod rewrite \
    && sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/public>|' /etc/apache2/apache2.conf

# Establecer directorio de trabajo en public para comandos runtime
WORKDIR /var/www/html

# Permisos y creación de directorios de cache de Laravel
RUN mkdir -p storage framework bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache framework \
    && chmod -R 775 storage bootstrap/cache framework

# Instalar dependencias de Composer optimizadas para producción
env COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1 \
    COMPOSER_DISABLE_XDEBUG=1 \
    composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

# Copiar .env y generar APP_KEY si es necesario\RUN cp .env.example .env && php artisan key:generate || true

# Limpiar cachés de Laravel (rutas, configuración, vistas)
RUN php artisan route:clear \
    && php artisan config:clear \
    && php artisan view:clear

# Exponer el puerto y arrancar Apache
EXPOSE 80
CMD ["apache2-foreground"]
