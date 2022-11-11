<?php

namespace Spatie\LaravelMorphMapGenerator\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelMorphMapGenerator\MorphMapGeneratorServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            MorphMapGeneratorServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('morph-map-generator.autogenerate', false);
    }
}
