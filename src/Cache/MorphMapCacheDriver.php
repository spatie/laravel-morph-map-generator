<?php

namespace Spatie\LaravelMorphMapGenerator\Cache;

interface MorphMapCacheDriver
{
    public function clear(): void;

    public function exists(): bool;

    public function set(array $morphMap): void;

    public function get(): array;
}
