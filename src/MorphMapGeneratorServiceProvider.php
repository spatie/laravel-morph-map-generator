<?php

namespace Spatie\LaravelMorphMapGenerator;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelMorphMapGenerator\Cache\FilesystemMorphMapCacheDriver;
use Spatie\LaravelMorphMapGenerator\Cache\MorphMapCacheDriver;
use Spatie\LaravelMorphMapGenerator\Commands\CacheMorphMapCommand;
use Spatie\LaravelMorphMapGenerator\Commands\ClearMorphMapCommand;

class MorphMapGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/morph-map-generator.php', 'morph-map-generator');

        $this->bindCacheDriver();

        $cache = $this->app->make(MorphMapCacheDriver::class);

        if ($cache->exists()) {
            Relation::morphMap($cache->get());

            return;
        }

        if (config('morph-map-generator.autogenerate')) {
            $discoveredModels = DiscoverModels::create()
                ->withPaths(config('morph-map-generator.paths'))
                ->withBaseModels(config('morph-map-generator.base_models'))
                ->ignoreModels(config('morph-map-generator.ignored_models'))
                ->discover();

            $morphMap = MorphMapGenerator::create()
                ->generate($discoveredModels);

            Relation::morphMap($morphMap);

            return;
        }
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/morph-map-generator.php' => config_path('morph-map-generator.php'),
            ], 'config');

            $this->commands([
                CacheMorphMapCommand::class,
                ClearMorphMapCommand::class,
            ]);
        }
    }

    private function bindCacheDriver()
    {
        $config = config('morph-map-generator.cache') ?? [];

        $this->app->bind(MorphMapCacheDriver::class, fn () => $this->app->make(
            $config['type'] ?? FilesystemMorphMapCacheDriver::class,
            ['config' => $config]
        ));
    }
}
