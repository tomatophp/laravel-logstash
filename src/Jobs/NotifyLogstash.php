<?php

namespace TomatoPHP\LaravelLogstash\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Monolog\LogRecord;
use TomatoPHP\LaravelLogstash\Client\Logstash;

class NotifyLogstash implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public LogRecord $record
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Logstash::send($this->record);
    }
}
