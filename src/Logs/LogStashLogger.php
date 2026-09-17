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
     * Create the Monolog instance for the "logstash" custom log channel.
     *
     * @param  array<string, mixed>  $config
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger($config['name'] ?? 'logstash');

        $logger->pushHandler(new LogStashHandler(
            level: $config['level'] ?? config('laravel-logstash.level', 'debug'),
            bubble: $config['bubble'] ?? true,
            url: $config['url'] ?? null,
            queue: isset($config['queue']) ? (bool) $config['queue'] : null,
            queueConnection: $config['queue_connection'] ?? null,
            queueName: $config['queue_name'] ?? null,
        ));

        $logger->pushProcessor(new WebProcessor);
        $logger->pushProcessor(new GitProcessor);
        $logger->pushProcessor(new ProcessIdProcessor);
        $logger->pushProcessor(new MemoryUsageProcessor);
        $logger->pushProcessor(new MemoryPeakUsageProcessor);

        return $logger;
    }
}
