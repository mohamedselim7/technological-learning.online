FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    jpeg-dev \
    libwebp-dev \
    freetype-dev \
    icu-dev \
    libxml2-dev \
    postgresql-dev \
    build-base \
    autoconf \
    g++

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) pdo_mysql zip gd intl opcache pcntl

# Install Composer
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate application key
RUN php artisan key:generate --force

# Run migrations and seeders
RUN php artisan migrate --force
RUN php artisan db:seed --force

# Expose port 8000 for Laravel development server
EXPOSE 8000

# Start PHP-FPM
CMD ["php-fpm"]

