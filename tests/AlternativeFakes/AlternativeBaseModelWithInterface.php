<?php

namespace Spatie\LaravelMorphMapGenerator\Tests\AlternativeFakes;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelMorphMapGenerator\HasMorphMap;

// An alternative base model

abstract class AlternativeBaseModelWithInterface extends Model implements HasMorphMap
{
  public function getMorphClass(): string
  {
      return 'post';
  }
}
