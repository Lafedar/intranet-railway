FROM php:8.1-apache

# Instalar extensiones necesarias para Laravel y tus dependencias
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
    && docker-php-ext-install pdo_mysql mbstring zip gd xml

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto al contenedor
COPY . /var/www/html

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Permisos y configuraciones de Apache
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Apache permite override para Laravel
RUN echo '<Directory /var/www/html>\n\
    AllowOverride All\n\
</Directory>' >> /etc/apache2/apache2.conf

# Instalar dependencias de Laravel (sin dev para producción)
RUN composer install --no-dev --optimize-autoloader

# Generar app key si no existe
RUN if [ ! -f ".env" ]; then cp .env.example .env; fi && \
    php artisan key:generate

EXPOSE 80