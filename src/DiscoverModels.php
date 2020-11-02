<?php

namespace Spatie\LaravelMorphMapGenerator;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class DiscoverModels
{
    /** @var string[] */
    protected array $paths = [];

    /** @var string[] */
    protected array $baseModels = [];

    /** @var string[] */
    protected array $ignoredModels = [];

    protected string $basePath;

    protected string $rootNamespace = '';

    public function __construct()
    {
        $this->basePath = base_path();
    }

    public static function create(): self
    {
        return new self();
    }

    public function withPaths(array $paths): self
    {
        $this->paths = $paths;

        return $this;
    }

    public function withBaseModels(array $baseModels): self
    {
        $this->baseModels = $baseModels;

        return $this;
    }

    public function ignoreModels(array $ignoredModels): self
    {
        $this->ignoredModels = $ignoredModels;

        return $this;
    }

    public function withBasePath(string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }

    public function withRootNamespace(string $rootNamespace): self
    {
        $this->rootNamespace = $rootNamespace;

        return $this;
    }

    public function discover(): Collection
    {
        if (empty($this->paths)) {
            return collect();
        }

        $files = (new Finder())->files()->in($this->paths);

        $ignoredFiles = $this->getAutoloadedFiles(base_path('composer.json'));

        return collect($files)
            ->reject(fn(SplFileInfo $file) => in_array($file->getPathname(), $ignoredFiles))
            ->map(fn(SplFileInfo $file) => $this->fullyQualifiedClassNameFromFile($file))
            ->filter(fn(string $modelClass) => $this->shouldClassBeIncluded($modelClass))
            ->map(fn(string $modelClass) => new ReflectionClass($modelClass))
            ->reject(fn(ReflectionClass $reflection) => $reflection->isAbstract());
    }

    private function fullyQualifiedClassNameFromFile(SplFileInfo $file): string
    {
        $class = trim(Str::replaceFirst($this->basePath, '', $file->getRealPath()), DIRECTORY_SEPARATOR);

        $class = str_replace(
            [DIRECTORY_SEPARATOR, 'App\\'],
            ['\\', app()->getNamespace()],
            ucfirst(Str::replaceLast('.php', '', $class))
        );

        return $this->rootNamespace . $class;
    }

    private function shouldClassBeIncluded(string $class): bool
    {
        if (in_array($class, $this->ignoredModels)) {
            return false;
        }

        foreach ($this->baseModels as $baseModelClass) {
            if (is_subclass_of($class, $baseModelClass)) {
                return true;
            }
        }

        return false;
    }

    private function getAutoloadedFiles($composerJsonPath): array
    {
        if (! file_exists($composerJsonPath)) {
            return [];
        }

        $basePath = Str::before($composerJsonPath, 'composer.json');

        $composerContents = json_decode(file_get_contents($composerJsonPath), true);

        $paths = array_merge(
            $composerContents['autoload']['files'] ?? [],
            $composerContents['autoload-dev']['files'] ?? []
        );

        return array_map(fn(string $path) => realpath($basePath . $path), $paths);
    }
}
