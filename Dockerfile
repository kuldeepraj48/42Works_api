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

# Laravel optimizations
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan config:cache

# Copy nginx config to correct location
COPY nginx.conf /etc/nginx/nginx.conf

# Supervisor config
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port
EXPOSE 80

# Start Nginx + PHP-FPM together
CMD ["/usr/bin/supervisord"]
