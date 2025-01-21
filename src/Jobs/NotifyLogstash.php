<?php

namespace TomatoPHP\LaravelLogstash\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Monolog\LogRecord;
use TomatoPHP\LaravelLogstash\Client\Logstash;

class NotifyLogstash implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $record
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
