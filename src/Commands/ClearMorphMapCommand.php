<?php

namespace Spatie\LaravelMorphMapGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Spatie\LaravelMorphMapGenerator\Cache\MorphMapCacheDriver;

class ClearMorphMapCommand extends Command
{
    protected $signature = 'morph-map:clear';

    protected $description = 'Clear a cached version of the morph map';

    public function handle(MorphMapCacheDriver $cache): void
    {
        if (! $cache->exists()) {
            return;
        }

        $cache->clear();
    }
}
