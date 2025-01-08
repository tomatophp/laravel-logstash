<?php

namespace TomatoPHP\LaravelLogstash\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use TomatoPHP\LaravelLogstash\LaravelLogstashServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelLogstashServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', __DIR__.'/../database/database.sqlite');

        $app['config']->set('view.paths', [
            ...$app['config']->get('view.paths'),
            __DIR__.'/../resources/views',
        ]);
    }
}
