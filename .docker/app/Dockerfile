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
    supervisor

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy project files
COPY ../../ .

# Copy supervisor setup
COPY docking-worker.conf /etc/supervisor/conf.d/docking-worker.conf

# build
RUN composer install
RUN npm ci
RUN npm run build
RUN php artisan optimize
RUN php artisan storage:link

# Set permissions
RUN chown -R www-data:www-data storage
RUN chown -R www-data:www-data bootstrap/cache

# Visor bootstrapping
RUN supervisorctl reread
RUN supervisorctl update

# Start ALL
CMD supervisorctl start docking-worker:* && php-fpm
