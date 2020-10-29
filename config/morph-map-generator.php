<?php

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
