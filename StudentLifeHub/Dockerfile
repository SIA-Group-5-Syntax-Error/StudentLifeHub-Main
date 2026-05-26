FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && a2enmod rewrite

# Get modern Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory inside container
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Apache Document Root to point to Laravel's public directory
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]