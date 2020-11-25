## kaboomr

Guess who's back in the roomr.

This is a bookmarking service written using [Laravel](https://laravel.com) and [Laravel UI](https://github.com/laravel/ui).

## Installation

First set up a mysql database and update your .env file with the correct credentials.

Clone this repository
```bash
git clone https://github.com/joefearnley/kaboomr.git
cd kaboomr
```

Install dependencies
```bash
composer install
```

Migrate the data
```bash
php artisan migrate
```

## Testing
This project has a good amount of tests around it. To run those tests use the artisan command. 
```bash
php artisan test
```

## Deploying
This application is deployed to [Heroku](https://heroku.com).
