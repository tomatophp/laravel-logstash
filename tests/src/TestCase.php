<?php

namespace TomatoPHP\LaravelLogstash\Tests;

use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase as BaseTestCase;
use TomatoPHP\LaravelLogstash\LaravelLogstashServiceProvider;

abstract class TestCase extends BaseTestCase
{
    public const LOGSTASH_URL = 'https://logstash.test:8080';

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelLogstashServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.name', 'Testing');
        $app['config']->set('queue.default', 'sync');
        $app['config']->set('laravel-logstash.url', self::LOGSTASH_URL);
    }
}
