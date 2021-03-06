# Flight Advisor Code Test

## Technologies

    - Programming Language: PHP v7.4
    - Framework: Laravel v8.0
    - Database: MySql
    - Security: Laravel Breeze token auth
    - Containers: Docker

## Before Starting
Copy .env.example into .env
```bash
cp .env.example .env
```

Install required laravel package
```bash
composer require laravel/sail --dev
```

If you get a memory limit error, run the command as:
```bash
COMPOSER_MEMORY_LIMIT=-1 composer require laravel/sail --dev
```

Generate a secret key for JWT auth
```bash
php artisan jwt:secret
```

## Running in Docker
The service can also be run completely in Docker by running
```bash
docker-compose up -d
```

## Initial data
Run migrations
```bash
php artisan migrate
```

After running migrations database will be populated with tables needed for the project.
There are also two pre-populated users:

admin:
- email: admin@user.com
- password: admin123 

regular:
- email: regular@user.com
- password: regular


## Known Issues
 - web and api guards are not set up to work at the same time. Right now only API endpoints are working, no UI login
 - problems setting up swagger-ui . API documentation can be found in 'swagger.yml'
 - All routes search not working, only direct route search works