<?php

namespace TomatoPHP\LaravelLogstash\Tests;

use Illuminate\Support\Facades\Log;
use TomatoPHP\LaravelLogstash\Jobs\NotifyLogstash;

it('can log to logstash', function () {
    Log::channel('logstash')->info('Log to logstash');

    expect(NotifyLogstash::class)->toBeClass();
});
