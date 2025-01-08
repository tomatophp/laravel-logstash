<?php

namespace TomatoPHP\LaravelLogstash\Tests;

use Illuminate\Support\Facades\Log;

it('can log to logstash', function () {
    $response = Log::info('Hello World');

    assertTrue($response);
});
