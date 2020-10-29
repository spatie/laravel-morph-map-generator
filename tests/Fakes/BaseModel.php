<?php

namespace Spatie\LaravelMorphMapGenerator\Tests\Fakes;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelMorphMapGenerator\Tests\TestClasses\ModelHasNoMorphClass;

// The base model from which all other models extend
abstract class BaseModel extends Model
{
    public function getMorphClass()
    {
        throw ModelHasNoMorphClass::create(static::class);
    }
}
