<?php

namespace Spatie\LaravelMorphMapGenerator\Tests\Fakes;

use Illuminate\Database\Eloquent\Model;

// Does not extend the BaseModel::class
class OtherTypeModel extends Model
{
    public function getMorphClass()
    {
        return 'other_type';
    }
}
