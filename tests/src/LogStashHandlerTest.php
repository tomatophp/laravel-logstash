<?php

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use TomatoPHP\LaravelLogstash\Jobs\NotifyLogstash;
use TomatoPHP\LaravelLogstash\Logs\LogStashHandler;
use TomatoPHP\LaravelLogstash\Tests\TestCase;

it('builds a monolog logger with the logstash handler', function () {
    $handlers = Log::channel('logstash')->getLogger()->getHandlers();

    expect($handlers)->toHaveCount(1)
        ->and($handlers[0])->toBeInstanceOf(LogStashHandler::class);
});

it('queues a logstash formatted payload', function () {
    Queue::fake();

    Log::channel('logstash')->warning('Payment failed', ['order' => 42]);

    Queue::assertPushed(NotifyLogstash::class, function (NotifyLogstash $job) {
        expect($job->url)->toBe(TestCase::LOGSTASH_URL)
            ->and($job->record)->toHaveKeys(['@timestamp', '@version', 'host', 'message', 'type', 'channel', 'level', 'monolog_level', 'context', 'extra'])
            ->and($job->record['message'])->toBe('Payment failed')
            ->and($job->record['type'])->toBe('Testing')
            ->and($job->record['channel'])->toBe('logstash')
            ->and($job->record['level'])->toBe('WARNING')
            ->and($job->record['@timestamp'])->toBeString()
            ->and($job->record['context'])->toBe(['order' => 42])
            ->and($job->record['extra'])->toHaveKeys(['process_id', 'memory_usage', 'memory_peak_usage']);

        return true;
    });
});

it('sends the payload to the logstash url over http', function () {
    Http::fake([TestCase::LOGSTASH_URL => Http::response(['ok' => true])]);

    Log::channel('logstash')->info('Hello Logstash', ['user' => 1]);

    Http::assertSentCount(1);
    Http::assertSent(fn (Request $request) => $request->url() === TestCase::LOGSTASH_URL
        && $request->method() === 'POST'
        && $request->isJson()
        && $request['message'] === 'Hello Logstash'
        && $request['level'] === 'INFO'
        && $request['context'] === ['user' => 1]
        && is_string($request['@timestamp']));
});

it('normalizes exceptions in the context into plain data', function () {
    Queue::fake();

    Log::channel('logstash')->error('Boom', ['exception' => new RuntimeException('Something broke')]);

    Queue::assertPushed(NotifyLogstash::class, function (NotifyLogstash $job) {
        expect($job->record['context']['exception'])->toBeArray()
            ->and($job->record['context']['exception']['class'])->toBe(RuntimeException::class)
            ->and($job->record['context']['exception']['message'])->toBe('Something broke')
            ->and(fn () => serialize($job))->not->toThrow(Throwable::class);

        return true;
    });
});

it('sends nothing when no logstash url is configured', function () {
    config()->set('laravel-logstash.url', null);
    Queue::fake();
    Http::fake();

    Log::channel('logstash')->info('Nowhere to go');

    Queue::assertNothingPushed();
    Http::assertNothingSent();
});

it('respects the configured minimum level', function () {
    config()->set('logging.channels.logstash.level', 'error');
    Queue::fake();

    Log::channel('logstash')->info('Too quiet');
    Log::channel('logstash')->error('Loud enough');

    Queue::assertPushed(NotifyLogstash::class, 1);
    Queue::assertPushed(NotifyLogstash::class, fn (NotifyLogstash $job) => $job->record['message'] === 'Loud enough');
});

it('uses the url from the channel config', function () {
    config()->set('logging.channels.logstash.url', 'https://other-logstash.test');
    Queue::fake();

    Log::channel('logstash')->info('Routed');

    Queue::assertPushed(NotifyLogstash::class, fn (NotifyLogstash $job) => $job->url === 'https://other-logstash.test');
});

it('dispatches on the configured queue connection and name', function () {
    config()->set('laravel-logstash.queue_connection', 'redis');
    config()->set('laravel-logstash.queue_name', 'logs');
    Queue::fake();

    Log::channel('logstash')->info('Queued');

    Queue::assertPushed(NotifyLogstash::class, fn (NotifyLogstash $job) => $job->connection === 'redis' && $job->queue === 'logs');
});

it('sends directly without the queue when queue is disabled', function () {
    config()->set('laravel-logstash.queue', false);
    Queue::fake();
    Http::fake([TestCase::LOGSTASH_URL => Http::response()]);

    Log::channel('logstash')->info('Direct');

    Queue::assertNothingPushed();
    Http::assertSent(fn (Request $request) => $request['message'] === 'Direct');
});

it('can be the default log channel', function () {
    config()->set('logging.default', 'logstash');
    Queue::fake();

    Log::info('Default channel');

    Queue::assertPushed(NotifyLogstash::class, fn (NotifyLogstash $job) => $job->record['message'] === 'Default channel');
});

it('does not crash the app when logstash returns an error', function () {
    Http::fake([TestCase::LOGSTASH_URL => Http::response('down', 500)]);

    Log::channel('logstash')->error('Still running');

    Http::assertSentCount(1);
});

it('does not crash the app when logstash is unreachable', function () {
    Http::fake(fn () => throw new ConnectionException('Connection refused'));

    expect(fn () => Log::channel('logstash')->error('Still running'))->not->toThrow(Throwable::class);
});

it('does not crash the app when the queue is unavailable', function () {
    config()->set('queue.default', 'missing-connection');

    expect(fn () => Log::channel('logstash')->error('Still running'))->not->toThrow(Throwable::class);
});
