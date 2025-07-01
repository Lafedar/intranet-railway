FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones de PHP necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    wkhtmltopdf \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd dom xml

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el código fuente de Laravel al contenedor
COPY . /var/www/html

# Ajustar DocumentRoot de Apache a la carpeta public y permitir .htaccess
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|<Directory /var/www/>|<Directory /var/www/html/public>|' /etc/apache2/apache2.conf \
    && a2enmod rewrite

# Definir el directorio de trabajo en /var/www/html
WORKDIR /var/www/html

# Crear y dar permisos a carpetas necesarias de Laravel
RUN mkdir -p bootstrap/cache \
    && chmod -R 775 storage \
    && chmod -R 775 bootstrap/cache \
    && chown -R www-data:www-data .

# Instalar dependencias de Laravel sin paquetes de desarrollo\RUN composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

# Copiar .env.example como .env si no existe, y generar APP_KEY (ignora errores si falla)
RUN if [ ! -f "/var/www/html/.env" ]; then cp /var/www/html/.env.example /var/www/html/.env; fi \
    && php /var/www/html/artisan key:generate || true

# Limpiar cachés de Laravel en cada build\RUN php /var/www/html/artisan route:clear && \
    php /var/www/html/artisan config:clear && \
    php /var/www/html/artisan view:clear || true

# Exponer el puerto 80 y arrancar Apache limpiando caches en runtime
CMD bash -lc "php /var/www/html/artisan route:clear && php /var/www/html/artisan config:clear && php /var/www/html/artisan view:clear && apache2-foreground"

EXPOSE 80
