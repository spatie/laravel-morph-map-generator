<?php

namespace Spatie\LaravelMorphMapGenerator\Tests;

use Spatie\LaravelMorphMapGenerator\Cache\FilesystemMorphMapCacheDriver;
use Spatie\LaravelMorphMapGenerator\Cache\MorphMapCacheDriver;
use Spatie\LaravelMorphMapGenerator\Commands\CacheMorphMapCommand;
use Spatie\LaravelMorphMapGenerator\Commands\ClearMorphMapCommand;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class MorphMapCommandsTest extends TestCase
{
    private TemporaryDirectory $temporaryDirectory;

    public function setUp(): void
    {
        parent::setUp();

        $this->temporaryDirectory = (new TemporaryDirectory())->create();

        $this->app->extend(MorphMapCacheDriver::class, fn() => resolve(FilesystemMorphMapCacheDriver::class, [
            'config' => [
                'type' => FilesystemMorphMapCacheDriver::class,
                'path' => $this->temporaryDirectory->path('cached'),
            ],
        ]));
    }

    /** @test */
    public function it_can_cache_a_morph_map()
    {
        $this->artisan(CacheMorphMapCommand::class)
            ->assertExitCode(0)
            ->run();

        $this->assertTrue(file_exists($this->temporaryDirectory->path('cached/morph-map.php')));
    }

    /** @test */
    public function it_can_remove_a_cached_morph_map()
    {
        $this->artisan(CacheMorphMapCommand::class)
            ->assertExitCode(0)
            ->run();

        $this->artisan(ClearMorphMapCommand::class)
            ->assertExitCode(0)
            ->run();

        $this->assertFalse(file_exists($this->temporaryDirectory->path('cached/morph-map.php')));
    }
}
