<?php

namespace MalvikLab\LaravelHttpLogger\Providers;

use Illuminate\Support\ServiceProvider;
use MalvikLab\LaravelHttpLogger\Http\Middleware\LaravelHttpLoggerMiddleware;

class LaravelHttpLoggerServiceProvider extends ServiceProvider
{
    private $packageTag = 'malviklab-laravel-http-logger';

    public function boot()
    {
        $tag = 'malviklab-laravel-http-logger';
        $configFileName = sprintf('%s.php', $tag);

        $this->publishes([
            sprintf('%s/../../config/%s', __DIR__, $configFileName) => config_path($configFileName),
        ], sprintf('%s-config', $tag));

        $this->publishes([
            sprintf('%s/../../database/migrations/', __DIR__) => database_path('migrations'),
        ], sprintf('%s-migrations', $tag));

        $router = $this->app['router'];
        $router->aliasMiddleware($this->packageTag, LaravelHttpLoggerMiddleware::class);
    }

    public function register()
    {
        $tag = 'malviklab-laravel-http-logger';
        $configFileName = sprintf('%s.php', $tag);

        /*
        $this->publishes([
            sprintf('%s/../../config/%s', __DIR__, $configFileName) => config_path($configFileName),
        ], sprintf('%s-config', $tag));
        */

        //$this->mergeConfigFrom(__DIR__.'/config/'.$this->_packageTag.'.php', $this->_packageTag);

        /*
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadSeedsFrom();
        */

        //$this->publishFiles();


    }
}