<?php

namespace Spatie\LaravelMorphMapGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearMorphMapCommand extends Command
{
    protected $signature = 'morph-map:clear';

    protected $description = 'Clear a cached version of the morph map';

    public function handle(Filesystem $filesystem): void
    {
        ['cache_path' => $cachePath] = config('morph-map-generator');

        $cachePath = "{$cachePath}/morph-map.php";

        if (! $filesystem->exists($cachePath)) {
            return;
        }

        $filesystem->delete($cachePath);
    }
}
