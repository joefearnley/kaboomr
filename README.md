## kaboomr

[Guess who's back in the roomr.](https://www.youtube.com/watch?v=Tjyb7MPnuj4)

This is a bookmarking service written using [Laravel](https://laravel.com) and [Laravel UI](https://github.com/laravel/ui).

## Installation

See [Laravel's server requirements](https://laravel.com/docs/8.x#server-requirements) for the system requirements.

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

- Run the server
```bash
php artisan serve
```

## Testing
This project has a good amount of tests around it. To run those tests use the artisan command. 
```bash
php artisan test
```

## Deploying
My version of this site runs on a [Digital Ocean](https://www.digitalocean.com/) droplet and is a work in progress. I would like to get a CI tool set up to run the tests when push to Github and possibly push to Digital Ocean as well.
