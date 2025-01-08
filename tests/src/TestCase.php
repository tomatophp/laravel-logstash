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
        $app['config']->set('logging.channels', 'logstash');
        $app['config']->set('queue.default', 'sync');
    }
}
