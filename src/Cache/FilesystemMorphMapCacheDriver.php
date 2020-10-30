<?php

namespace Spatie\LaravelMorphMapGenerator\Cache;

use Illuminate\Filesystem\Filesystem;

class FilesystemMorphMapCacheDriver implements MorphMapCacheDriver
{
    private string $path;

    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem, array $config = [])
    {
        $this->filesystem = $filesystem;
        $this->path = $config['path'] ?? storage_path('app/morph-map-generator');
    }

    public function clear(): void
    {
        if (! $this->exists()) {
            return;
        }

        $this->filesystem->delete("{$this->path}/morph-map.php");
    }

    public function exists(): bool
    {
        return $this->filesystem->exists("{$this->path}/morph-map.php");
    }

    public function set(array $morphMap): void
    {
        $this->filesystem->makeDirectory($this->path, 0755, true, true);

        $this->filesystem->put(
            "{$this->path}/morph-map.php",
            $this->stub($morphMap)
        );
    }

    public function get(): array
    {
        return require "{$this->path}/morph-map.php";
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
