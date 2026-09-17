<?php

namespace TomatoPHP\LaravelLogstash\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use TomatoPHP\LaravelLogstash\Client\Logstash;

class NotifyLogstash implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    /**
     * @param  array<string, mixed>  $record  the Logstash-formatted record
     */
    public function __construct(
        public array $record,
        public ?string $url = null,
    ) {}

    public function handle(): void
    {
        Logstash::send($this->record, $this->url);
    }
}
