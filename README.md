# Automatically generate morph maps in your Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-morph-map-generator.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-morph-map-generator)
![Tests](https://github.com/spatie/laravel-morph-map-generator/workflows/Tests/badge.svg)
![Psalm](https://github.com/spatie/laravel-morph-map-generator/workflows/Psalm/badge.svg)
![Check & fix styling](https://github.com/spatie/laravel-morph-map-generator/workflows/Check%20&%20fix%20styling/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-morph-map-generator.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-morph-map-generator)

With this package, you shouldn't worry about forgetting to add models to your application's morph map. Each model will autoregister itself in the morph map. The only thing you should do is implementing the `getMorphClass` method on your models like this:

```php
class Post extends Model
{
    public function getMorphClass(): string {
        return 'post';
    }
}
```

From now on, the `Post` model will be represented as `post` within your morph map.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-morph-map-generator.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-morph-map-generator)

We invest many resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-morph-map-generator
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Spatie\LaravelMorphMapGenerator\MorphMapGeneratorServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Autogenerate
    |--------------------------------------------------------------------------
    |
    | When enabled, morph maps will be automatically generated when the
    | application is booted.
    |
    */

    'autogenerate' => true,

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | Within these paths, the package will search for models to be included
    | in the generated morph map.
    |
    */

    'paths' => [
        app_path(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Base models
    |--------------------------------------------------------------------------
    |
    | Only models that extend from one of the base models defined here will
    | be included in the generated morph map.
    |
    */

    'base_models' => [
        Illuminate\Database\Eloquent\Model::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Ignored models
    |--------------------------------------------------------------------------
    |
    | When generating the morph map, these models will not be included.
    |
    */

    'ignored_models' => [],


    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Morph maps can be cached, there's a `FilesystemMorphMapCacheDriver` which
    | stores the morph map as a file in a directory or you can also use the
    | Laravel built-in cache by using `LaravelMorphMapCacheDriver`.
    |
    | Both drivers have their own config:
    | - `FilesystemMorphMapCacheDriver` requires a `path` to store the file
    | - `LaravelMorphMapCacheDriver` requires a `key` for storage
    |
    */

    'cache' => [
        'type' => Spatie\LaravelMorphMapGenerator\Cache\FilesystemMorphMapCacheDriver::class,
        'path' => storage_path('app/morph-map-generator'),
    ]
];
```

## Usage

First, you have to implement `getMorphClass` for the models you want to include in your morph map. We suggest you create a new base model class in your application from which all your models extend. So you could throw an exception when `getMorphClass` was not yet implemented:

```php
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    public function getMorphClass()
    {
        throw new Exception('The model should implement `getMorphClass`');
    }
}
```

When a model is not implementing `getMorphClass`, it will throw an exception when building the generated morph map, making it possible to quickly find models that do not have a morph map entry. 

When `autogenerate` is enabled in the `morph-map-generator` config file, the morph map in your application will be dynamically generated each time the application boots. This is great in development environments since each time your application boots, the morph map is regenerated. For performanc reasons, you should cache the dynamically generated morph map by running the following command:

```bash
php artisan morph-map:cache
```

Removing a cached morph map can be done by running:

```php
php artisan morph-map:clear
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ruben Van Assche](https://github.com/rubenvanassche)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
