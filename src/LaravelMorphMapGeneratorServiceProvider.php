<?php

namespace Spatie\LaravelMorphMapGenerator;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelMorphMapGenerator\Commands\CacheMorphMapCommand;
use Spatie\LaravelMorphMapGenerator\Commands\ClearMorphMapCommand;

class LaravelMorphMapGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/morph-map-generator.php', 'morph-map-generator');

        $cachePath = config('morph-map-generator.cache_path') . '/morph-map.php';

        if (file_exists($cachePath)) {
            Relation::morphMap(require $cachePath);

            return;
        }

        if (config('morph-map-generator.autogenerate')) {
            $discoverer = DiscoverModels::create()
                ->withPaths(config('morph-map-generator.paths'))
                ->withBaseModels(config('morph-map-generator.base_models'))
                ->ignoreModels(config('morph-map-generator.ignored_models'));

            Relation::morphMap($discoverer->discover());

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
}
