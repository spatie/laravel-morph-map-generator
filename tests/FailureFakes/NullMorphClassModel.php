<?php

namespace Spatie\LaravelMorphMapGenerator\Tests\FailureFakes;

use Spatie\LaravelMorphMapGenerator\Tests\Fakes\BaseModel;

class NullMorphClassModel extends BaseModel
{
    public function getMorphClass()
    {
        return null;
    }
}
