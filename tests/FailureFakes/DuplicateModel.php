<?php

namespace Spatie\LaravelMorphMapGenerator\Tests\FailureFakes;

use Spatie\LaravelMorphMapGenerator\Tests\Fakes\BaseModel;

// When included with GeneralModel it has the same morph class
class DuplicateModel extends BaseModel
{
    public function getMorphClass()
    {
        return 'general';
    }
}
