# Docker of DocKing

DocKing ships Docker Images too, in case you want boot it up as fast as you can for development, even production usage.

## Development Image

Development Image offers all-in-one image. You only need to run the image to use and that's all.

Dev mode uses SQLite (`docking.sqlite` file is inside the image).

```bash
docker run -p 8888:80 shipsaas/docking-dev -d
```

Note: you need to run `gotenberg` image independently. 
Check out our [`docker-compose.yaml`](../docker-compose.yaml) for example.

## Production Build

For production, we highly recommended to build the private image and push it from your end. So you will have over controls on:

- Configurations & Environment Variables
- Private usage
- blah blah...

### Preparation Steps

- Clone Project / Get Latest Tag
- `composer install`
- `npm ci`;
- `npm run build`
- Prepare your desire ".env" file
- Build

### Build Image

```bash
docker build -f Dockerfile.prod -t your-org/docking:<version>

docker build -f ./docker/Dockerfile.prod -t shipsaas/docking:1.0.0
```

## Note

We don't add the "DB" into `docker-compose.yml`. You can choose or add it based on your need.

IRL, we always use the GCP SQL or AWS RDS (a lot of features) instead of Docker, won't we?
