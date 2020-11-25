## kaboomr

Guess who's back in the roomr.

This is a bookmarking service written using [Laravel](https://laravel.com) and [Laravel UI](https://github.com/laravel/ui).

## Installation

- Clone this repository
```bash
git clone https://github.com/joefearnley/kaboomr.git
cd kaboomr
```

- Set up a mysql database and update your `.env` file with the correct credentials.

- Also, add the correct email settings in your `.env` file

- Install dependencies
```bash
composer install
```

- Migrate the data
```bash
php artisan migrate
```

## Testing
This project has a good amount of tests around it. To run those tests use the artisan command. 
```bash
php artisan test
```

## Deploying
This application is deployed to [Heroku](https://heroku.com), which you can follow the instruction here on how to do that:

[Getting Started with Laravel on Heroku](https://devcenter.heroku.com/articles/getting-started-with-laravel)

Of course it can also be deploy to any server a Laravel application can be deployed to.
