<?php

namespace Spatie\LaravelMorphMapGenerator\Tests\Fakes;

// A typical model
class GeneralModel extends BaseModel
{
    public function getMorphClass()
    {
        return 'general';
    }
}
