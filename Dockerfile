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

# Ajustar Apache para servir desde public y habilitar módulos
RUN a2enmod rewrite headers \
    && sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/public>|' /etc/apache2/apache2.conf

# Permisos y creación de directorios de cache de Laravel
RUN mkdir -p storage framework bootstrap/cache \
    && chown -R www-data:www-data storage framework bootstrap/cache \
    && chmod -R 775 storage framework bootstrap/cache

# Instalar dependencias de Composer optimizadas para producción
RUN env COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1 \
    COMPOSER_DISABLE_XDEBUG=1 \
    composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

# Copiar .env y generar APP_KEY si es necesario
RUN if [ ! -f "/var/www/html/.env" ]; then \
      cp /var/www/html/.env.example /var/www/html/.env; \
    fi \
    && php /var/www/html/artisan key:generate || true

# Limpiar cachés de Laravel (rutas, configuración, vistas)
RUN php /var/www/html/artisan route:clear \
    && php /var/www/html/artisan config:clear \
    && php /var/www/html/artisan view:clear

# Exponer el puerto y arrancar Apache
EXPOSE 80
CMD ["apache2-foreground"]
