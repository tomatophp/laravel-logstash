<?php

use Illuminate\Support\ServiceProvider;
use TomatoPHP\LaravelLogstash\LaravelLogstashServiceProvider;
use TomatoPHP\LaravelLogstash\Logs\LogStashLogger;

it('boots the service provider', function () {
    expect(app()->getProvider(LaravelLogstashServiceProvider::class))
        ->toBeInstanceOf(LaravelLogstashServiceProvider::class);
});

it('merges the package config', function () {
    expect(config('laravel-logstash'))
        ->toHaveKeys(['url', 'level', 'queue', 'queue_connection', 'queue_name', 'timeout'])
        ->and(config('laravel-logstash.level'))->toBe('debug')
        ->and(config('laravel-logstash.queue'))->toBeTrue()
        ->and(config('laravel-logstash.timeout'))->toBe(5);
});

it('publishes the config file', function () {
    $paths = ServiceProvider::pathsToPublish(LaravelLogstashServiceProvider::class, 'laravel-logstash-config');

    expect($paths)->toHaveCount(1)
        ->and(array_key_first($paths))->toEndWith('laravel-logstash.php')
        ->and(realpath(array_key_first($paths)))->toBeFile()
        ->and(array_values($paths)[0])->toBe(config_path('laravel-logstash.php'));
});

it('registers the logstash log channel', function () {
    expect(config('logging.channels.logstash'))->toBe([
        'driver' => 'custom',
        'via' => LogStashLogger::class,
    ]);
});

it('keeps a logstash channel the application already defines', function () {
    $custom = ['driver' => 'custom', 'via' => LogStashLogger::class, 'level' => 'error'];
    config()->set('logging.channels.logstash', $custom);

    (new LaravelLogstashServiceProvider(app()))->boot();

    expect(config('logging.channels.logstash'))->toBe($custom);
});
