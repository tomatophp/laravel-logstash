<?php

namespace TomatoPHP\LaravelLogstash;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class LaravelLogstashServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register Config file
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-logstash.php', 'laravel-logstash');

        // Publish Config
        $this->publishes([
            __DIR__ . '/../config/laravel-logstash.php' => config_path('laravel-logstash.php'),
        ], 'laravel-logstash-config');
    }

    public function boot(): void
    {
        Config::set('logging.channels.logstash', [
            'driver' => 'custom',
            'via' => \TomatoPHP\LaravelLogstash\Logs\LogStashLogger::class,
            'url' => config('laravel-logstash.url'),
        ]);
    }
}
