![Screenshot](https://raw.githubusercontent.com/tomatophp/laravel-logstash/master/art/screenshot.jpg)

# Laravel Logstash

[![Latest Stable Version](https://poser.pugx.org/tomatophp/laravel-logstash/version.svg)](https://packagist.org/packages/tomatophp/laravel-logstash)
[![License](https://poser.pugx.org/tomatophp/laravel-logstash/license.svg)](https://packagist.org/packages/tomatophp/laravel-logstash)
[![Downloads](https://poser.pugx.org/tomatophp/laravel-logstash/d/total.svg)](https://packagist.org/packages/tomatophp/laravel-logstash)

Elastic Logstash integration as a log driver for Laravel Apps

## Installation

```bash
composer require tomatophp/laravel-logstash
```

after install your package please run this command

```bash
php artisan laravel-logstash:install
```

finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\LaravelLogstash\LaravelLogstashPlugin::make())
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="laravel-logstash-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="laravel-logstash-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="laravel-logstash-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="laravel-logstash-migrations"
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](mailto:info@3x1.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
