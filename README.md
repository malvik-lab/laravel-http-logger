# Laravel Http Logger
Log every request and response of [Laravel PHP Framework](https://github.com/laravel/laravel).

The package saves all the data of the requests and responses in the "request_log" table, but if you want you can use a custom adapter.

## Installation
```
$ composer require malvik-lab/laravel-http-logger
```

## Publish config file
```
$ php artisan vendor:publish --tag=malviklab-laravel-http-logger-config
```

## Publish migration file
```
$ php artisan vendor:publish --tag=malviklab-laravel-http-logger-migrations
```

## Run migration
```
$ php artisan migrate
```

## Use on your routes
```php
Route::middleware(['malviklab-laravel-http-logger'])->group(function () {
    // your routes here
});
```

## Configuration
In the configuration file you can set any values present in the requests and responses to be hidden (eg password or access token), the word with which to hide and the adapter to be used for saving.
```php
<?php
// config/malviklab-laravel-http-logger.php

return [
    'storageAdapter' => MalvikLab\LaravelHttpLogger\Http\Middleware\Adapters\DbAdapter::class,
    'hiddenText' => '[ *** HIDDEN *** ]',
    'keysToHide' => [
        'Authorization',
        'password',
        'token',
    ],
];
```
