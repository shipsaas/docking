#!/usr/bin/env sh
set -e

# Run user scripts, if they exist
for f in /var/www/html/.docker/scripts/*.sh; do
    # Bail out this loop if any script exits with non-zero status code
    bash "$f" || break
done

echo "Welcome to DocKing";
echo "Starting the application using supervisor...";
exec /usr/bin/supervisord --nodaemon
