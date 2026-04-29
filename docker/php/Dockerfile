# PHP 8.3 con Apache nos da un entorno clasico y compatible con Drupal.
FROM php:8.3-apache

# Instalamos librerias del sistema necesarias para extensiones PHP habituales en Drupal.
# Cada paquete da soporte a alguna de las extensiones que se instalan debajo.
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libicu-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        gd \
        intl \
        opcache \
        pdo_mysql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Activamos rewrite en Apache, necesario para las URLs limpias de Drupal.
RUN a2enmod rewrite

# Usamos un virtual host especifico para Drupal con /web como raiz publica.
COPY docker/php/apache-drupal.conf /etc/apache2/sites-available/000-default.conf

# Composer se usa para instalar Drupal y sus dependencias de forma reproducible.
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Docker Compose monta aqui la raiz del proyecto.
WORKDIR /var/www/html
