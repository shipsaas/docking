FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor \
    nginx

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copy project files
COPY . .
COPY ./.docker/app/docking-worker.conf /etc/supervisor/conf.d/
COPY ./.docker/app/docking-host.conf /etc/nginx/conf.d/default.conf

RUN php artisan optimize
RUN php artisan storage:link

# Set permissions
RUN chown -R www-data:www-data storage
RUN chown -R www-data:www-data bootstrap/cache

# Nginx
RUN rm /etc/nginx/sites-enabled/default

EXPOSE 80

# Start ALL
CMD ["/usr/bin/supervisord", "-n"]
