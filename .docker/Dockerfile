### Docking Development Image
# Single image to rule them all
# PHP 8.2
# SQLite
# Nginx
# Local Storage
# Supervisor to run 5 concurrent workers

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
RUN docker-php-ext-install pdo mbstring exif pcntl bcmath gd

# Copy project files
COPY . .
COPY ./.docker/docking-worker.conf /etc/supervisor/conf.d/
COPY ./.docker/docking-host.conf /etc/nginx/conf.d/default.conf

RUN touch docking.sqlite
RUN php artisan optimize
RUN php artisan storage:link
RUN php artisan migrate

# Set permissions
RUN chown -R www-data:www-data storage
RUN chown -R www-data:www-data bootstrap/cache

# Nginx remove default site
RUN rm /etc/nginx/sites-enabled/default

EXPOSE 80

# Start ALL
CMD ["/usr/bin/supervisord", "-n"]
