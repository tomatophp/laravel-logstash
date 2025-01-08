<?php

namespace TomatoPHP\LaravelLogstash\Logs;

use Monolog\Logger;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\WebProcessor;

class LogStashLogger
{
    /**
     * Create a custom Monolog instance.
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('logstash');
        $logger->pushHandler(new LogStashHandler);
        $logger->pushProcessor(new WebProcessor);
        $logger->pushProcessor(new GitProcessor);
        $logger->pushProcessor(new ProcessIdProcessor);
        $logger->pushProcessor(new MemoryUsageProcessor);
        $logger->pushProcessor(new MemoryPeakUsageProcessor);

        return $logger;
    }
}
