<?php

namespace TomatoPHP\LaravelLogstash\Logs;

use Illuminate\Contracts\Bus\Dispatcher;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Throwable;
use TomatoPHP\LaravelLogstash\Client\Logstash;
use TomatoPHP\LaravelLogstash\Jobs\NotifyLogstash;

class LogStashHandler extends AbstractProcessingHandler
{
    public function __construct(
        int | string | Level $level = Level::Debug,
        bool $bubble = true,
        protected ?string $url = null,
        protected ?bool $queue = null,
        protected ?string $queueConnection = null,
        protected ?string $queueName = null,
    ) {
        parent::__construct($level, $bubble);
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LogstashFormatter((string) (config('app.name') ?: 'laravel'));
    }

    protected function write(LogRecord $record): void
    {
        $url = filled($this->url) ? $this->url : config('laravel-logstash.url');

        if (blank($url)) {
            return;
        }

        $payload = is_string($record->formatted) ? json_decode($record->formatted, true) : $record->formatted;

        if (! is_array($payload)) {
            return;
        }

        try {
            if (! ($this->queue ?? (bool) config('laravel-logstash.queue', true))) {
                Logstash::send($payload, $url);

                return;
            }

            $job = new NotifyLogstash($payload, $url);
            $job->onConnection($this->queueConnection ?? config('laravel-logstash.queue_connection'));
            $job->onQueue($this->queueName ?? config('laravel-logstash.queue_name'));

            app(Dispatcher::class)->dispatch($job);
        } catch (Throwable) {
            // Logging must never break the application.
        }
    }
}
