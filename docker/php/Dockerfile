# PHP 8.3 with Apache gives us a classic Drupal-friendly runtime.
FROM php:8.3-apache

# Install system libraries required by common Drupal PHP extensions.
# Each package supports a concrete extension installed below.
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libicu-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        gd \
        intl \
        opcache \
        pdo_mysql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite rules, required by Drupal clean URLs.
RUN a2enmod rewrite

# Use a Drupal-specific Apache virtual host with /web as the document root.
COPY docker/php/apache-drupal.conf /etc/apache2/sites-available/000-default.conf

# Composer will be used in the next phase to install Drupal and dependencies.
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# The project root is mounted here by Docker Compose.
WORKDIR /var/www/html
