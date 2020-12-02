<?php

return [
    /*
    * When enabled, morph maps will be automatically generated when the
    * application is booted.
    */

    'autogenerate' => true,

    /*
     * Change the base path if the models don't reside in the default App namespace.
     * For example when your application is stored in `src/app` and your models
     * are placed in `src/domain` then you can use `src` as a base path.
     */

    'base_path' => '/',

    /*
    * Within these paths, the package will search for models to be included
    * in the generated morph map.
    */

    'paths' => [
        app_path(),
    ],

    /*
    * Only models that extend from one of the base models defined here will
    * be included in the generated morph map.
    */

    'base_models' => [
        Illuminate\Database\Eloquent\Model::class,
    ],

    /*
    * When generating the morph map, these models will not be included.
    */

    'ignored_models' => [],


    /*
    * Morph maps can be cached, there's a `FilesystemMorphMapCacheDriver` which
    * stores the morph map as a file in a directory or you can also use the
    * Laravel built-in cache by using `LaravelMorphMapCacheDriver`.
    *
    * Both drivers have their own config:
    * - `FilesystemMorphMapCacheDriver` requires a `path` to store the file
    * - `LaravelMorphMapCacheDriver` requires a `key` for storage
    */

    'cache' => [
        'type' => Spatie\LaravelMorphMapGenerator\Cache\FilesystemMorphMapCacheDriver::class,
        'path' => storage_path('app/morph-map-generator'),
    ],
];
