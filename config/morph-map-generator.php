<?php

return [
    /*
    * When enabled, morph maps will be automatically generated when the
    * application is booted.
    */

    'autogenerate' => true,

    /**
     * Change the base directory if the models don't reside in the default App namespace.
     *
     * For example, the base directory would become 'src' if:
     * - Application is in src/App
     * - Models are in src/Domain
     */

    'base_directory' => '/',

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
