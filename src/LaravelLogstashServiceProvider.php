<?php

namespace TomatoPHP\LaravelLogstash;

use Illuminate\Support\ServiceProvider;
use TomatoPHP\LaravelLogstash\Logs\LogStashLogger;

class LaravelLogstashServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-logstash.php', 'laravel-logstash');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-logstash.php' => config_path('laravel-logstash.php'),
            ], 'laravel-logstash-config');
        }

        /**
         * Register the "logstash" channel unless the application already defines its own.
         */
        if (! is_array(config('logging.channels.logstash'))) {
            config()->set('logging.channels.logstash', [
                'driver' => 'custom',
                'via' => LogStashLogger::class,
            ]);
        }
    }
}
