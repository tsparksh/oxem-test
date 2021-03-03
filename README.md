## Installation
1. `git clone https://github.com/tsparksh/oxem-test`
2. `cp .env.example .env`
3. `docker-compose up -d`
4. `docker-compose exec app bash`

##### Inside a container
1. `composer install`
2. `php artisan migrate`
3. `php artisan db:seed`
4. `php artisan key:generate`

##### Access via http://127.0.0.1:1338
