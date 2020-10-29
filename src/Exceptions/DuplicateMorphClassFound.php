<?php

namespace Spatie\LaravelMorphMapGenerator\Exceptions;

use RuntimeException;

class DuplicateMorphClassFound extends RuntimeException
{
    public static function create(string $currentModelClass, string $previousModelClass)
    {
        return new self("The morph class used in: {$currentModelClass}` cannot be the same as the one used in: `{$previousModelClass}`");
    }
}
