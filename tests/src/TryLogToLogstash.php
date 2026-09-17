<?php

namespace TomatoPHP\LaravelLogstash\Tests;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use TomatoPHP\LaravelLogstash\Jobs\NotifyLogstash;

it('can log to logstash', function () {
    Queue::fake();

    Log::channel('logstash')->info('Log to logstash');

    Queue::assertPushed(NotifyLogstash::class, fn (NotifyLogstash $job) => $job->record['message'] === 'Log to logstash');
});
