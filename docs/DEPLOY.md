# Deploy DocKing

## Docker

Checkout our built images (to be updated), feel free to pick them up and use anytime.

## Normal VPS (EC2/Compute Engine) 

Note: this doc assumes you are using Ubuntu.

### Dependencies Installation

- Install Nginx/Apache
- Install PHP 8.2
- (Optional) Install MySQL 8 or PostgreSQL 13+

### Project Installation

- Download the latest version in [RELEASE](https://github.com/shipsaas/docking/releases) page
- Extract the archive
- Run `composer install`
- Copy the `.env.example` to `.env` and set all the needed variables
- Run `php artisan key:generate`

To be continued...
