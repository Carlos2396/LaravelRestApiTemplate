# LaravelRestApiTemplate
Rest api build with Laravel 5.7, protected with passport tokens. It includes Spatie/Permissions for role and permission management.

Instructions to run

First run
  composer install
  
Update the .env and .env.testing files with proper configurations
Now run the migrations and seeds if you want.
  php artisan migrate --seed

Now initialize posspor
  php artisan passport:install
  
If you want to run the tests, also execute
  php artisan migrate --seed --env=testing
  php artisan passport:install --env=testing

And then
  composer test
  
To run a single test class
  ./vendor/bin/phpunit --filter ClassName
