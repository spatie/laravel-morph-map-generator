<?php

namespace Spatie\LaravelMorphMapGenerator\Tests\Fakes;

use Exception;
use Illuminate\Database\Eloquent\Model;

// Will execute some events, which it shouldn't
class EventModel extends BaseModel
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected static function booted()
    {
        static::creating(function (Model $model): void {
            throw new Exception('Model should not be calling events');
        });
    }

    public function getMorphClass()
    {
        return 'event';
    }
}
