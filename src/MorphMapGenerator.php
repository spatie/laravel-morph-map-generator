<?php

namespace Spatie\LaravelMorphMapGenerator;

use Exception;
use Illuminate\Support\Collection;
use ReflectionClass;
use Spatie\LaravelMorphMapGenerator\Exceptions\DuplicateMorphClassFound;
use Spatie\LaravelMorphMapGenerator\Exceptions\MorphClassCouldNotBeResolved;

class MorphMapGenerator
{
    public static function create(): self
    {
        return new self();
    }

    public function generate(Collection $models): array
    {
        $usedMorphs = [];

        return $models
            ->mapWithKeys(fn (ReflectionClass $reflection) => $this->resolveMorphFromModelClass($reflection))
            ->reject(fn (string $morph) => class_exists($morph))
            ->mapWithKeys(function (string $morph, string $modelClass) use (&$usedMorphs) {
                if (array_key_exists($morph, $usedMorphs)) {
                    throw DuplicateMorphClassFound::create($modelClass, $usedMorphs[$morph]);
                }

                $usedMorphs[$morph] = $modelClass;

                return [$morph => $modelClass];
            })->toArray();
    }

    private function resolveMorphFromModelClass(ReflectionClass $reflection): ?array
    {
        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $reflection->newInstanceWithoutConstructor();

        try {
            $morph = $model->getMorphClass();
        } catch (Exception $exception) {
            throw MorphClassCouldNotBeResolved::exceptionThrown($reflection->getName(), $exception);
        }

        if (empty($morph)) {
            throw MorphClassCouldNotBeResolved::nullReturned($reflection->getName());
        }

        return [$reflection->getName() => $morph];
    }
}
