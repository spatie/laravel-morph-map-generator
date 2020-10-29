<?php

namespace Spatie\LaravelMorphMapGenerator\Tests\FailureFakes;

// Does not implement a `getMorphClass` method and will throw an exception
use Spatie\LaravelMorphMapGenerator\Tests\Fakes\BaseModel;

class ExceptionMorphClassModel extends BaseModel
{
}
