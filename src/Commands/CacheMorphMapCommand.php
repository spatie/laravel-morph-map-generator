<?php

namespace Spatie\LaravelMorphMapGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Spatie\LaravelMorphMapGenerator\DiscoverModels;

class CacheMorphMapCommand extends Command
{
    protected $signature = 'morph-map:cache';

    protected $description = 'Generate a cached morph map based upon your models';

    public function handle(Filesystem $filesystem): void
    {
        [
            'paths' => $paths,
            'base_models' => $baseModels,
            'ignored_models' => $ignoredModels,
            'cache_path' => $cachePath,
        ] = config('morph-map-generator');

        $morphMap = DiscoverModels::create()
            ->ignoreModels($ignoredModels)
            ->withPaths($paths)
            ->withBaseModels($baseModels)
            ->discover();

        $filesystem->makeDirectory($cachePath, 0755, true, true);

        $filesystem->put(
            "{$cachePath}/morph-map.php",
            $this->stub($morphMap)
        );

        $this->info('Morph map cached');
    }

    private function stub(array $morphMap): string
    {
        $stub = '<?php' . PHP_EOL . PHP_EOL . 'return [' . PHP_EOL;

        foreach ($morphMap as $morph => $class) {
            $stub .= "    '{$morph}' => '{$class}'," . PHP_EOL;
        }

        $stub .= '];' . PHP_EOL;

        return $stub;
    }
}
