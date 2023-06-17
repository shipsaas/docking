### Docking Development Image - Octane Mode
# Single image to rule them all
# PHP 8.2
# SQLite
# Local Storage
# Supervisor to run 5 concurrent workers
FROM ghcr.io/roadrunner-server/roadrunner:latest AS roadrunner
FROM php:8.2-fpm

COPY --from=roadrunner /usr/bin/rr /usr/local/bin/rr

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
    nginx \
    wkhtmltopdf

# Install PHP extensions
RUN docker-php-ext-install pdo mbstring exif pcntl bcmath gd

# Copy project files
COPY . .
COPY ./.docker/docking-octane.conf /etc/supervisor/conf.d/
COPY ./.docker/docking-host-octane.conf /etc/nginx/conf.d/default.conf

RUN php artisan optimize
RUN php artisan storage:link
RUN php artisan migrate

RUN chown -R www-data:www-data storage
RUN chown -R www-data:www-data storage/app
RUN chmod -R 777 storage/logs
RUN chmod -R 777 docking.sqlite


# Nginx remove default site
RUN rm /etc/nginx/sites-enabled/default

EXPOSE 80

# Start ALL
CMD ["/usr/bin/supervisord", "-n"]
