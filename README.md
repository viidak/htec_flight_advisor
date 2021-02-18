# Flight Advisor Code Test

## Technologies

    - Programming Language: PHP v7.4
    - Framework: Laravel v8.0
    - Database: MySql
    - Security: Laravel Breeze token auth
    - Containers: Docker

## Running in Docker
The service can also be run completely in Docker by running
```bash
docker-compose up -d
```

## Initial data
After running migrations database will be populated with tables needed for the project.
There are also two pre-populated users:

admin:
- email: admin@user.com
- password: admin123 

regular:
- email: regular@user.com
- password: regular
