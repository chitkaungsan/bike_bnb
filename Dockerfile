# Stage 1: Build stage
FROM php:8.4-cli AS build

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip curl git libonig-dev libxml2-dev nodejs npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy ONLY composer files first (for caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy package.json (for node caching)
COPY package.json package-lock.json ./
RUN npm ci

# Copy the rest of the application
COPY . .

# Build Front-end assets
RUN npm run build

# Generate autoloader now that files are copied
RUN composer dump-autoload --optimize

# 🧹 CLEANUP: Remove node_modules to save space before copying
RUN rm -rf node_modules
# 🧹 CLEANUP: Remove .git folder
RUN rm -rf .git

# Stage 2: Production image
FROM php:8.4-apache

# Set document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN a2enmod rewrite
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Install runtime dependencies (often needed for PHP extensions)
RUN apt-get update && apt-get install -y libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy application from build stage (Now it's much smaller!)
COPY --from=build /app /var/www/html

# Permissions (This runs faster on smaller folders)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
