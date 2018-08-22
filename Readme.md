# HeyURL! Code Challenge

## Getting Started

1. Clone repository

2. Setup Homestead(optional)

3. Install dependencies
```sh
$ composer install
```

1. Clone repository
2. Composer install
3. Modify .env
  ```
    DB_DATABASE=hey_url_challenge_laravel
    DB_USERNAME=<-- your local pg role
  ```
4. createdb hey_url_challenge_laravel
5. php artisan migrate
6. php artisan serve
7. Open localhost:8000

## Considerations

1. Check routes defined in `routes/web.rb`
2. Check controller actions in `app/Http/Controllers/UrlController.rb`
3. Check views in `resources/views/urls/`
4. Check models in `app/*`
5. Google Charts is already added to display charts, you can use any library
6. Use the `jenssegers/agent` lib already installed to extract information about each click tracked
