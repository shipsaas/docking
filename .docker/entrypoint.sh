#!/usr/bin/env sh
set -e

echo "
 ____             _  ___
|  _ \  ___   ___| |/ (_)_ __   __ _
| | | |/ _ \ / __| ' /| | '_ \ / _  |
| |_| | (_) | (__| . \| | | | | (_| |
|____/ \___/ \___|_|\_\_|_| |_|\__, |
                               |___/
";

# Run user scripts, if they exist
for f in /var/www/html/.docker/scripts/*.sh; do
    # Bail out this loop if any script exits with non-zero status code
    bash "$f" || break
done

if [ "$DB_CONNECTION" = "sqlite" ]; then
    echo "For SQLite users, please note down the UID & GID, then run CHOWN on the host machine"
    id www-data
fi

echo "Starting the application using supervisor...";
exec /usr/bin/supervisord --nodaemon
