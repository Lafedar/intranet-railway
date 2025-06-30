FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones de PHP
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

# Copiar el código fuente al contenedor
COPY . /var/www/html

# Definir el directorio de trabajo
WORKDIR /var/www/html

# Activar mod_rewrite de Apache
RUN a2enmod rewrite \
    && echo '<Directory /var/www/html>\n\
        AllowOverride All\n\
    </Directory>' >> /etc/apache2/apache2.conf

# Crear y dar permisos a carpetas necesarias
RUN mkdir -p /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Generar app key si no hay .env
RUN if [ ! -f ".env" ]; then cp .env.example .env; fi && \
    php artisan key:generate || true

# Exponer el puerto
EXPOSE 80
