![Screenshot](https://raw.githubusercontent.com/tomatophp/laravel-logstash/master/arts/screenshot.jpg)

# Laravel Logstash Log Channel

[![Latest Stable Version](https://poser.pugx.org/tomatophp/laravel-logstash/version.svg)](https://packagist.org/packages/tomatophp/laravel-logstash)
[![License](https://poser.pugx.org/tomatophp/laravel-logstash/license.svg)](https://packagist.org/packages/tomatophp/laravel-logstash)
[![Downloads](https://poser.pugx.org/tomatophp/laravel-logstash/d/total.svg)](https://packagist.org/packages/tomatophp/laravel-logstash)

Elastic Logstash log channel for Laravel apps

## Installation

```bash
composer require tomatophp/laravel-logstash
```

on your env add your host with port as a direct http connection on your env, and change the log channel to logstash

```dotenv
LOGSTASH_HOST=https://log.tomatophp.com
LOG_CHANNEL=logstash
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="laravel-logstash-config"
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
