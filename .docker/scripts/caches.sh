#!/usr/bin/env bash

echo "Running config:cache...";
php /var/www/html/artisan config:cache --no-ansi -q
echo "Successfully cached the configuration";

echo "Running route:cache...";
php /var/www/html/artisan route:cache --no-ansi -q
echo "Successfully cached the routes";

echo "Running view:cache...";
php /var/www/html/artisan view:cache --no-ansi -q
echo "Successfully cached the views";
