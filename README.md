![Screenshot](https://raw.githubusercontent.com/tomatophp/laravel-logstash/master/arts/screenshot.jpg)

# Laravel Logstash Log Channel

[![Dependabot Updates](https://github.com/tomatophp/laravel-logstash/actions/workflows/dependabot/dependabot-updates/badge.svg)](https://github.com/tomatophp/laravel-logstash/actions/workflows/dependabot/dependabot-updates)
[![PHP Code Styling](https://github.com/tomatophp/laravel-logstash/actions/workflows/fix-php-code-styling.yml/badge.svg)](https://github.com/tomatophp/laravel-logstash/actions/workflows/fix-php-code-styling.yml)
[![Tests](https://github.com/tomatophp/laravel-logstash/actions/workflows/tests.yml/badge.svg)](https://github.com/tomatophp/laravel-logstash/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/tomatophp/laravel-logstash/version.svg)](https://packagist.org/packages/tomatophp/laravel-logstash)
[![License](https://poser.pugx.org/tomatophp/laravel-logstash/license.svg)](https://packagist.org/packages/tomatophp/laravel-logstash)
[![Downloads](https://poser.pugx.org/tomatophp/laravel-logstash/d/total.svg)](https://packagist.org/packages/tomatophp/laravel-logstash)

Elastic Logstash log channel for Laravel apps. Every log record is formatted with Monolog's `LogstashFormatter` and posted as JSON to a Logstash [HTTP input](https://www.elastic.co/guide/en/logstash/current/plugins-inputs-http.html), through a queued job by default.

## Compatibility

| Version | Laravel      | PHP   | Branch   |
|---------|--------------|-------|----------|
| 2.x     | 12.x, 13.x   | 8.2+  | `master` |
| 1.x     | 10.x, 11.x   | 8.1+  | `v1`     |

## Installation

```bash
composer require tomatophp/laravel-logstash
```

The service provider is auto-discovered and registers a `logstash` log channel. Add the Logstash HTTP input URL (host with port) to your `.env` and use the channel:

```dotenv
LOGSTASH_HOST=https://logstash.example.com:8080
LOG_CHANNEL=logstash
```

To keep your local log file as well, add `logstash` to the `stack` channel instead:

```dotenv
LOG_CHANNEL=stack
LOG_STACK=single,logstash
```

When `LOGSTASH_HOST` is empty nothing is sent. A failing or unreachable Logstash never throws, so logging can not break your app.

## Queue

Records are sent by the `TomatoPHP\LaravelLogstash\Jobs\NotifyLogstash` job, so run a queue worker (or use the `sync` queue). To send directly over HTTP during the request, set `LOGSTASH_QUEUE=false`.

## Configuration

| Env                          | Default | Description                                      |
|------------------------------|---------|--------------------------------------------------|
| `LOGSTASH_HOST`              | `null`  | Logstash HTTP input URL                          |
| `LOGSTASH_LEVEL`             | `debug` | Minimum level sent to Logstash                   |
| `LOGSTASH_QUEUE`             | `true`  | Send through the queued job                      |
| `LOGSTASH_QUEUE_CONNECTION`  | `null`  | Queue connection for the job (app default)       |
| `LOGSTASH_QUEUE_NAME`        | `null`  | Queue name for the job (app default)             |
| `LOGSTASH_TIMEOUT`           | `5`     | HTTP timeout in seconds                          |

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-logstash-config"
```

To customise the channel, define it yourself in `config/logging.php`; the package will not overwrite it. Channel keys override the package config:

```php
'logstash' => [
    'driver' => 'custom',
    'via' => \TomatoPHP\LaravelLogstash\Logs\LogStashLogger::class,
    'url' => env('LOGSTASH_HOST'),
    'level' => 'error',
    'queue' => true,
    'queue_connection' => 'redis',
    'queue_name' => 'logs',
],
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](mailto:info@3x1.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Testing

if you like to run `PEST` testing just use this command

```bash
composer test
```

## Code Style

if you like to fix the code style just use this command

```bash
composer format
```

## PHPStan

if you like to check the code by `PHPStan` just use this command

```bash
composer analyse
```

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
