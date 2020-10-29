<?php

namespace Spatie\LaravelMorphMapGenerator\Tests\TestClasses;

use Exception;

class ModelHasNoMorphClass extends Exception
{
    public static function create(string $model)
    {
        return new self("Model `{$model}` has no morph class.");
    }
}
