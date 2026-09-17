<?php

return [
    /**
     * Logstash HTTP input URL (host with port), for example https://logstash.example.com:8080.
     * When empty, nothing is sent.
     */
    'url' => env('LOGSTASH_HOST'),

    /**
     * Minimum log level sent to Logstash.
     */
    'level' => env('LOGSTASH_LEVEL', 'debug'),

    /**
     * Send records through the NotifyLogstash queued job (true) or directly over HTTP (false).
     */
    'queue' => env('LOGSTASH_QUEUE', true),

    /**
     * Queue connection and queue name used by the NotifyLogstash job (null = application default).
     */
    'queue_connection' => env('LOGSTASH_QUEUE_CONNECTION'),

    'queue_name' => env('LOGSTASH_QUEUE_NAME'),

    /**
     * HTTP timeout in seconds for a single request to Logstash.
     */
    'timeout' => env('LOGSTASH_TIMEOUT', 5),
];
