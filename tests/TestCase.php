<?php

namespace Spatie\LaravelMorphMapGenerator\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelMorphMapGenerator\LaravelMorphMapGeneratorServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelMorphMapGeneratorServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('morph-map-generator.autogenerate', false);
    }
}
