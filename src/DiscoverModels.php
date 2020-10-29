<?php

namespace Spatie\LaravelMorphMapGenerator;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use Spatie\LaravelMorphMapGenerator\Exceptions\DuplicateMorphClassFound;
use Spatie\LaravelMorphMapGenerator\Exceptions\MorphClassCouldNotBeResolved;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class DiscoverModels
{
    /** @var string[] */
    private array $paths = [];

    /** @var string[] */
    private array $baseModels = [];

    /** @var string[] */
    private array $ignoredModels = [];

    private string $basePath;

    private string $rootNamespace = '';

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

    public function discover(): array
    {
        if (empty($this->paths)) {
            return [];
        }

        $files = (new Finder())->files()->in($this->paths);

        $ignoredFiles = $this->getAutoloadedFiles(base_path('composer.json'));

        return collect($files)
            ->reject(fn(SplFileInfo $file) => in_array($file->getPathname(), $ignoredFiles))
            ->map(fn(SplFileInfo $file) => $this->fullQualifiedClassNameFromFile($file))
            ->filter(fn(string $modelClass) => $this->shouldClassBeIncluded($modelClass))
            ->mapWithKeys(fn(string $modelClass) => $this->resolveMorphFromModelClass($modelClass))
            ->filter()
            ->pipe(function (Collection $collection) {
                $usedMorphClasses = [];

                return $collection->mapWithKeys(function (string $morph, string $modelClass) use (&$usedMorphClasses) {
                    if (array_key_exists($morph, $usedMorphClasses)) {
                        throw DuplicateMorphClassFound::create($modelClass, $usedMorphClasses[$morph]);
                    }

                    $usedMorphClasses[$morph] = $modelClass;

                    return [$morph => $modelClass];
                });
            })
            ->toArray();
    }

    private function fullQualifiedClassNameFromFile(SplFileInfo $file): string
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
            if (! is_subclass_of($class, $baseModelClass)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            return ! $reflection->isAbstract();
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

    private function resolveMorphFromModelClass($modelClass): ?array
    {
        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = new $modelClass;

        try {
            $morph = $model->getMorphClass();
        } catch (Exception $exception) {
            throw MorphClassCouldNotBeResolved::exceptionThrown($modelClass, $exception);
        }

        if (empty($morph)) {
            throw MorphClassCouldNotBeResolved::nullReturned($modelClass);
        }

        if (class_exists($morph)) {
            return [$modelClass => null];
        }

        return [$modelClass => $morph];
    }
}
