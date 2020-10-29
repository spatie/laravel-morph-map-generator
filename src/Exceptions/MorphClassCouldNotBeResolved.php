<?php

namespace Spatie\LaravelMorphMapGenerator\Exceptions;

use Exception;
use RuntimeException;

class MorphClassCouldNotBeResolved extends RuntimeException
{
    public static function nullReturned(string $modelClass)
    {
        return new self("Could not get the morph class from: `{$modelClass}`, it returned null");
    }

    public static function exceptionThrown(string $modelClass, Exception $exception)
    {
        return new self("Could not get the morph class from: `{$modelClass}`, it returned the following exception: {$exception->getMessage()}");
    }
}
