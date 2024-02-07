#!/usr/bin/env bash

echo "Running migration...";
php /var/www/html/artisan migrate --no-ansi -q --force 
echo "Successfully migrated";
