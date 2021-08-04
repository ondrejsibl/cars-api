# Simple Car API

This is an API based on https://github.com/drivvn/backend-task

## Installation

 * Run `composer install`
 * Create a mysql database called `cars` and edit `DATABASE_URL` variable in `.env` and `phpunit.xml.dist` files
 * Run `php bin/console doctrine:migrations:migrate`
 * Create a mysql database called `cars_test` and run `php bin/console doctrine:migrations:migrate --env=test`

## API Endpoints

```
POST /cars
GET /car/<id>
DELETE /cars/<id>
GET /cars
```
## Running Tests

 * Run `php vendor/bin/phpunit tests`

## Request and Response Examples
### POST /cars
#### REQUEST
```json
{
    "make": "Ford",
    "model": "Focus",
    "color": "black",
    "buildDate": "2018-09-05"
}
```
#### RESPONSE
```json
{
    "data": {
        "id": 3
    }
}
```

### GET /cars/<id>

#### RESPONSE
```json
{
    "data": {
        "id": 2,
        "make": "Ford",
        "model": "Focus",
        "buildDate": "2018-09-05T00:00:00+00:00",
        "color": {
            "id": 3,
            "name": "white"
        }
    }
}
```

```json
{
    "error": "Car not found"
}
```
