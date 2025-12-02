FROM php:8.2-fpm

# System packages
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git

# Install PostgreSQL extension
RUN docker-php-ext-install pdo pdo_pgsql

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan config:cache

CMD ["php-fpm"]
