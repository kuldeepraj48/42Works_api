FROM php:8.2-fpm

# Install system packages
RUN apt-get update && apt-get install -y \
    libpng-dev \
    unzip \
    git \
    nginx \
    supervisor

# Install PHP MySQL drivers
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Cache config & routes
RUN php artisan config:cache && \
    php artisan route:cache

RUN mkdir -p storage/logs && \
    touch storage/logs/laravel.log

RUN chown -R www-data:www-data storage bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache && \
    chmod 664 storage/logs/laravel.log

COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

# Run migration at startup (recommended place)
CMD ["bash", "-lc", "php artisan migrate --force && /usr/bin/supervisord"]