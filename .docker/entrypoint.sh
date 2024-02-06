#!/usr/bin/env sh

# Run user scripts, if they exist
for f in /var/www/html/.docker/scripts/*.sh; do
    # Bail out this loop if any script exits with non-zero status code
    bash "$f" || break
done

echo "Starting the application using supervisor...";
exec /usr/bin/supervisord --nodaemon