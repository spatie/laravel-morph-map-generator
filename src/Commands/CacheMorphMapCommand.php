<?php

namespace Spatie\LaravelMorphMapGenerator\Commands;

use Illuminate\Console\Command;
use Spatie\LaravelMorphMapGenerator\Cache\MorphMapCacheDriver;
use Spatie\LaravelMorphMapGenerator\DiscoverModels;
use Spatie\LaravelMorphMapGenerator\MorphMapGenerator;

class CacheMorphMapCommand extends Command
{
    protected $signature = 'morph-map:cache';

    protected $description = 'Generate a cached morph map based upon your models';

    public function handle(MorphMapCacheDriver $cache): void
    {
        [
            'paths' => $paths,
            'base_models' => $baseModels,
            'ignored_models' => $ignoredModels,
        ] = config('morph-map-generator');

        $discoveredModels = DiscoverModels::create()
            ->ignoreModels($ignoredModels)
            ->withPaths($paths)
            ->withBaseModels($baseModels)
            ->discover();

        $morphMap = MorphMapGenerator::create()
            ->generate($discoveredModels);

        $cache->set($morphMap);

        $this->info('Morph map cached');
    }
}
