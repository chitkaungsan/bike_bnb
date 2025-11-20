# Stage 1: Build stage
FROM php:8.4-cli AS build

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip curl git libonig-dev libxml2-dev nodejs npm

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Configure git safe directory to avoid errors
RUN git config --global --add safe.directory /app

# Composer install
RUN composer install --no-dev --optimize-autoloader

# Build Vite assets
RUN npm install
RUN npm run build

# Stage 2: Production image
FROM php:8.4-apache

# Set document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN a2enmod rewrite
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Copy built application from build stage
COPY --from=build /app /var/www/html

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
