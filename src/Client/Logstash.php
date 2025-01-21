<?php

namespace TomatoPHP\LaravelLogstash\Client;

use Illuminate\Support\Facades\Http;
use Monolog\LogRecord;

class Logstash
{
    private ?string $url = null;

    public function __construct()
    {
        $this->url = config('laravel-logstash.url');
    }

    public static function send(array $record): bool
    {
        if ((new self)->url) {
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post((new self)->url, $record);

                if ($response->failed()) {
                    throw new \Exception('Logstash URL is not reachable');

                    return false;
                }

                return true;
            } catch (\Exception $e) {
                throw new \Exception('Logstash URL is not reachable');

                return false;
            }
        }

        return false;
    }
}
