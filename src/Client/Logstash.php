<?php

namespace TomatoPHP\LaravelLogstash\Client;

use Illuminate\Support\Facades\Http;
use Throwable;

class Logstash
{
    /**
     * Send a formatted log record to the Logstash HTTP input.
     *
     * Never throws: a Logstash outage must not break the application that is logging.
     *
     * @param  array<string, mixed>  $record
     */
    public static function send(array $record, ?string $url = null): bool
    {
        $url = filled($url) ? $url : config('laravel-logstash.url');

        if (blank($url)) {
            return false;
        }

        try {
            return Http::asJson()
                ->acceptJson()
                ->timeout((int) config('laravel-logstash.timeout', 5))
                ->post($url, $record)
                ->successful();
        } catch (Throwable) {
            return false;
        }
    }
}
