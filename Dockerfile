FROM php:8.2-fpm

# Install system packages
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    nginx \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Create required directories
RUN mkdir -p storage/logs bootstrap/cache

# Set correct permissions
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Copy configs
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

# DO NOT RUN MIGRATIONS HERE
CMD ["/usr/bin/supervisord"]