# File Storage Driver - DocKing

DocKing can use 2 Filesystem Drivers: 

- public (the local file storage)
- s3 (from AWS)

## The "public" prerequisite

Nothing, just put the `FILESYSTEM_DISK=public` and that's all.

## S3 prerequisite 

From the `.env` file, populate these variables:

```dotenv
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false
```
