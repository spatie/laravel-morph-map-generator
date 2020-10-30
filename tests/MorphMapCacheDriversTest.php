<?php

namespace Spatie\LaravelMorphMapGenerator\Tests;

use Closure;
use Illuminate\Filesystem\Filesystem;
use Psr\SimpleCache\CacheInterface;
use Spatie\LaravelMorphMapGenerator\Cache\FilesystemMorphMapCacheDriver;
use Spatie\LaravelMorphMapGenerator\Cache\LaravelMorphMapCacheDriver;
use Spatie\LaravelMorphMapGenerator\Cache\MorphMapCacheDriver;
use Spatie\LaravelMorphMapGenerator\Tests\Fakes\GeneralModel;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class MorphMapCacheDriversTest extends TestCase
{
    /**
     * @test
     * @dataProvider driversProvider
     *
     * @param \Closure $driverFactory
     */
    public function it_has_a_complete_cache_flow(Closure $driverFactory)
    {
        $driver = $driverFactory();

        $this->assertFalse($driver->exists());

        $driver->set(['general' => GeneralModel::class]);

        $this->assertTrue($driver->exists());

        $this->assertEquals(
            ['general' => GeneralModel::class],
            $driver->get()
        );

        $driver->clear();

        $this->assertFalse($driver->exists());
    }

    public function driversProvider(): array
    {
        return [
            [
                fn() => new FilesystemMorphMapCacheDriver(
                    resolve(Filesystem::class),
                    ['path' => (new TemporaryDirectory())->create()->path('cache')]
                ),
            ],
            [
                fn() => new LaravelMorphMapCacheDriver(
                    resolve(CacheInterface::class),
                    []
                ),
            ],
        ];
    }
}
