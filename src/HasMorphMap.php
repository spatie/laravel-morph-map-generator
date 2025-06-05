<?php

namespace Spatie\LaravelMorphMapGenerator;

interface HasMorphMap
{
    public function getMorphClass(): string;
}
