# Docker of DocKing

DocKing ships Docker Images too, in case you want boot it up as fast as you can for development, even production usage.

## Build Flow

- Clone Project / Get Latest Tag
- `composer install`
- `npm ci`;
- `npm run build`
- Prepare your desire ".env" file
- Build

## Note

We don't add the "DB" into `docker-compose.yml`. You can choose or add it based on your need.

IRL, we always use the GCP SQL or AWS RDS (a lot of features) instead of Docker, won't we?
