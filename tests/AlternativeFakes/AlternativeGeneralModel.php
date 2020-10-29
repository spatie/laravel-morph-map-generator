<?php

namespace Spatie\LaravelMorphMapGenerator\Tests\AlternativeFakes;

// An alternative general model
class AlternativeGeneralModel extends AlternativeBaseModel
{
    public function getMorphClass()
    {
        return 'alternativeGeneral';
    }
}
