# Automatically generate morph maps in your Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-morph-map-generator.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-morph-map-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-morph-map-generator/run-tests?label=tests)](https://github.com/spatie/laravel-morph-map-generator/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-morph-map-generator.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-morph-map-generator)

**Under construction, do not use**

With this package, you shouldn't worry about forgetting to add models to your application's morph map. Each model will autoregister itself in the morph map. The only thing you should do is implementing the `getMorphClass` method on your models like this:

```php
class Post extends Model
{
    public function getMorphClass(){
        return 'post';
    }
}
```

From now on, the `Post` model will be represented as `post` within your morph map.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/package-laravel-morph-map-generator-laravel.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/package-laravel-morph-map-generator-laravel)

We invest many resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-morph-map-generator
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Spatie\LaravelMorphMapGenerator\LaravelMorphMapGeneratorServiceProvider" --tag="config"
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

    'base_models' => [],

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
    | Cache location
    |--------------------------------------------------------------------------
    |
    | The cached versions of the morph map will be saved in this path.
    |
    */

    'cache_path' => storage_path('app/morph-map-generator'),
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
        throw new Exception('Model has `getMorphClass` not yet implemented!');
    }
}
```

When a model is not implementing `getMorphClass`, it will throw an exception when building the generated morph map, making it possible to quickly find models that do not have a morph map entry. 

When `autogenerate` is enabled in `morph-map-generator.php`, the morph map in your application is now dynamically generated each time the application boots. This is great in development environments since each time your application boots, the morph map is regenerated.

In production, you do not want this behavior. It takes a few moments to dynamically generate the morph map, precious time you don't want to lose in production. That's why you can cache the dynamically generated morph map by running the following command:

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
