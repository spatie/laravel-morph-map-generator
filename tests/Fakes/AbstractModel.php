<?php

namespace Spatie\LaravelMorphMapGenerator\Tests\Fakes;

// An abstract model that has no morph map
abstract class AbstractModel extends BaseModel
{
    public function getMorphClass()
    {
        return 'abstract';
    }
}
