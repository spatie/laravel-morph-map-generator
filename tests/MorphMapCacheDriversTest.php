<?php

use Illuminate\Filesystem\Filesystem;
use Psr\SimpleCache\CacheInterface;
use Spatie\LaravelMorphMapGenerator\Cache\FilesystemMorphMapCacheDriver;
use Spatie\LaravelMorphMapGenerator\Cache\LaravelMorphMapCacheDriver;
use Spatie\LaravelMorphMapGenerator\Cache\MorphMapCacheDriver;
use Spatie\LaravelMorphMapGenerator\Tests\Fakes\GeneralModel;
use Spatie\TemporaryDirectory\TemporaryDirectory;

it('has a complete cache flow', function (MorphMapCacheDriver $driver) {
    expect($driver->exists())->toBeFalse();

    $driver->set(['general' => GeneralModel::class]);

    expect($driver->exists())->toBeTrue();

    expect($driver->get())->toEqual(['general' => GeneralModel::class]);

    $driver->clear();

    expect($driver->exists())->toBeFalse();
})->with([
    [
        fn () => new FilesystemMorphMapCacheDriver(
            resolve(Filesystem::class),
            ['path' => (new TemporaryDirectory())->create()->path('cache')]
        ),
    ],
    [
        fn () => new LaravelMorphMapCacheDriver(
            resolve(CacheInterface::class),
            []
        ),
    ],
]);
