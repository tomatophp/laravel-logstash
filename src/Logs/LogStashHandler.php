<?php

namespace TomatoPHP\LaravelLogstash\Logs;

use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use TomatoPHP\LaravelLogstash\Jobs\NotifyLogstash;

class LogStashHandler extends AbstractProcessingHandler
{
    protected string $url;

    public function __construct(int|string|Level $level = Level::Debug, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function getDefaultFormatter(): LogstashFormatter
    {
        return new LogstashFormatter(config('app.name'));
    }

    protected function write(LogRecord $record): void
    {
        dispatch(new NotifyLogstash($record));
    }
}
