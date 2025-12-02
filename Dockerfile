FROM php:8.2-fpm

# Install system packages
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    nginx \
    supervisor

# Install PHP PostgreSQL drivers
RUN docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Laravel optimizations + caching
RUN php artisan config:cache && \
    php artisan route:cache

# Ensure log file exists
RUN mkdir -p storage/logs && \
    touch storage/logs/laravel.log

# Fix storage and cache folder permissions
RUN chmod -R 775 storage bootstrap/cache && \
    chmod 664 storage/logs/laravel.log

# Copy nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Supervisor config
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port
EXPOSE 80

# Start Nginx + PHP-FPM together
CMD ["/usr/bin/supervisord"]
