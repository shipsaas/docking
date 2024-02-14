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
    nginx \
    wkhtmltopdf

# Install PHP extensions
RUN docker-php-ext-install pdo mbstring exif pcntl bcmath gd

# Copy project files
COPY . .
COPY ./.docker/docking-worker.conf /etc/supervisor/conf.d/
COPY ./.docker/docking-host.conf /etc/nginx/conf.d/default.conf
COPY ./.docker/php-fpm.conf /usr/local/etc/php-fpm.d/

RUN cp .docker/entrypoint.sh /entrypoint
RUN chmod +x /entrypoint

# The bundle already built (outer layer), no need to keep this to save size
RUN rm -rf ./node_modules

RUN php artisan optimize
RUN php artisan storage:link

RUN chown -R www-data:www-data storage
RUN chown -R www-data:www-data storage/app
RUN chmod -R 777 storage/logs

RUN touch /var/www/html/docking.sqlite
RUN chown www-data:www-data docking.sqlite

# Nginx remove default site
RUN rm /etc/nginx/sites-enabled/default

EXPOSE 80

############# Default app ENV
ENV APP_ENV="production"
ENV APP_KEY="base64:/UnGygYvVBmIh+VgNhMj6MyI/ieXTtzUJsUL4OUtZGI="
ENV DB_CONNECTION="sqlite"
ENV DATABASE_URL="sqlite:////var/www/html/docking.sqlite"
ENV APP_URL="http://127.0.0.1:8000"

############# Storage ENV

# s3|local
ENV FILESYSTEM_DISK=public

# if select s3, these must be defined
ENV AWS_ACCESS_KEY_ID=""
ENV AWS_SECRET_ACCESS_KEY=""
ENV AWS_DEFAULT_REGION="ap-southeast-1"
ENV AWS_BUCKET="shipsaas-docking"

############# Docking Config
ENV DOCKING_PUBLIC_ACCESS_KEY=""
ENV DOCKING_CONSOLE_ENABLED=true
ENV DOCKING_CONSOLE_PASSWORD=""
ENV DOCKING_DEFAULT_PDF_DRIVER="gotenberg"
ENV DOCKING_GOTENBERG_ENDPOINT="http://127.0.0.1:9898"

# Start ALL
ENTRYPOINT ["/entrypoint"]
