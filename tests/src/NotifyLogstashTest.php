<?php

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use TomatoPHP\LaravelLogstash\Client\Logstash;
use TomatoPHP\LaravelLogstash\Jobs\NotifyLogstash;
use TomatoPHP\LaravelLogstash\Tests\TestCase;

it('is a queued job', function () {
    expect(new NotifyLogstash(['message' => 'x']))->toBeInstanceOf(ShouldQueue::class);
});

it('can be dispatched', function () {
    Queue::fake();

    NotifyLogstash::dispatch(['message' => 'Dispatched'], 'https://custom.test');

    Queue::assertPushed(NotifyLogstash::class, fn (NotifyLogstash $job) => $job->record === ['message' => 'Dispatched'] && $job->url === 'https://custom.test');
});

it('posts the record when handled', function () {
    Http::fake(['https://custom.test' => Http::response()]);

    (new NotifyLogstash(['message' => 'Handled'], 'https://custom.test'))->handle();

    Http::assertSent(fn (Request $request) => $request->url() === 'https://custom.test' && $request->data() === ['message' => 'Handled']);
});

it('falls back to the configured url', function () {
    Http::fake([TestCase::LOGSTASH_URL => Http::response()]);

    expect(Logstash::send(['message' => 'Config url']))->toBeTrue();

    Http::assertSent(fn (Request $request) => $request->url() === TestCase::LOGSTASH_URL);
});

it('returns false without a url', function () {
    config()->set('laravel-logstash.url', '');
    Http::fake();

    expect(Logstash::send(['message' => 'x']))->toBeFalse();

    Http::assertNothingSent();
});

it('returns false instead of throwing when logstash fails', function () {
    Http::fake([TestCase::LOGSTASH_URL => Http::response('error', 503)]);

    expect(Logstash::send(['message' => 'x']))->toBeFalse();
});

it('returns false instead of throwing when logstash is unreachable', function () {
    Http::fake(fn () => throw new ConnectionException('Connection refused'));

    expect(Logstash::send(['message' => 'x']))->toBeFalse();
});
