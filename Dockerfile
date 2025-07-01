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

# Definir el directorio de trabajo como /var/www/html
WORKDIR /var/www/html

# Activar mod_rewrite de Apache y permitir .htaccess en la carpeta public
RUN a2enmod rewrite \
    && echo '<Directory /var/www/html/public>\n        AllowOverride All\n    </Directory>' >> /etc/apache2/apache2.conf

# Crear y dar permisos a carpetas necesarias de Laravel
RUN mkdir -p /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

# Instalar dependencias de Laravel sin paquetes de desarrollo
RUN composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

# Copiar .env.example como .env si no existe, y generar APP_KEY (ignora errores si falla)
RUN if [ ! -f "/var/www/html/.env" ]; then cp /var/www/html/.env.example /var/www/html/.env; fi && \
    php /var/www/html/artisan key:generate || true

# Limpiar cachés de Laravel en cada deploy
RUN php /var/www/html/artisan route:clear && \
    php /var/www/html/artisan config:clear && \
    php /var/www/html/artisan view:clear || true

# Exponer el puerto 80 y arrancar Apache después de limpiar cachés
CMD bash -lc "php /var/www/html/artisan route:clear && php /var/www/html/artisan config:clear && php /var/www/html/artisan view:clear && apache2-foreground"

EXPOSE 80