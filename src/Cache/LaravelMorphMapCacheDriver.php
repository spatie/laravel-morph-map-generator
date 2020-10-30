<?php

namespace Spatie\LaravelMorphMapGenerator\Cache;

use Illuminate\Contracts\Cache\Repository;

class LaravelMorphMapCacheDriver implements MorphMapCacheDriver
{
    private Repository $cache;

    private string $key;

    public function __construct(Repository $cache, array $config = [])
    {
        $this->cache = $cache;
        $this->key = $config['key'] ?? 'spatie.morph-map-generator';
    }

    public function clear(): void
    {
        $this->cache->delete($this->key);
    }

    public function exists(): bool
    {
        return $this->cache->has($this->key);
    }

    public function set(array $morphMap): void
    {
        $this->cache->forever(
            $this->key,
            $morphMap
        );
    }

    public function get(): array
    {
        return $this->cache->get($this->key);
    }
}
