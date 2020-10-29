<?php

namespace Spatie\LaravelMorphMapGenerator\Tests;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelMorphMapGenerator\DiscoverModels;
use Spatie\LaravelMorphMapGenerator\Exceptions\DuplicateMorphClassFound;
use Spatie\LaravelMorphMapGenerator\Exceptions\MorphClassCouldNotBeResolved;
use Spatie\LaravelMorphMapGenerator\Tests\AlternativeFakes\AlternativeBaseModel;
use Spatie\LaravelMorphMapGenerator\Tests\AlternativeFakes\AlternativeGeneralModel;
use Spatie\LaravelMorphMapGenerator\Tests\FailureFakes\ExceptionMorphClassModel;
use Spatie\LaravelMorphMapGenerator\Tests\FailureFakes\NullMorphClassModel;
use Spatie\LaravelMorphMapGenerator\Tests\Fakes\AbstractModel;
use Spatie\LaravelMorphMapGenerator\Tests\Fakes\BaseModel;
use Spatie\LaravelMorphMapGenerator\Tests\Fakes\EventModel;
use Spatie\LaravelMorphMapGenerator\Tests\Fakes\GeneralModel;
use Spatie\LaravelMorphMapGenerator\Tests\Fakes\OtherTypeModel;

class DiscoverModelsTest extends TestCase
{
    private DiscoverModels $discoverer;

    public function setUp(): void
    {
        parent::setUp();

        $this->discoverer = DiscoverModels::create()
            ->withBasePath(realpath(__DIR__ . '/../'))
            ->withRootNamespace('Spatie\LaravelMorphMapGenerator\\');
    }

    /** @test */
    public function it_will_discover_models_in_a_path()
    {
        $this->discoverer
            ->withBaseModels([BaseModel::class])
            ->withPaths([__DIR__ . '/Fakes']);

        $discovered = $this->discoverer->discover();

        $this->assertEquals([
            'event' => EventModel::class,
            'general' => GeneralModel::class,
        ], $discovered);

        $this->assertNotContains([
            'abstract' => AbstractModel::class,
        ], $discovered);

        $this->assertNotContains([
            'other_type' => OtherTypeModel::class,
        ], $discovered);
    }

    /** @test */
    public function it_can_ignore_models()
    {
        $this->discoverer
            ->withBaseModels([BaseModel::class])
            ->withPaths([__DIR__ . '/Fakes'])
            ->ignoreModels([
                EventModel::class,
            ]);

        $discovered = $this->discoverer->discover();

        $this->assertArrayNotHasKey('event', $discovered);
        $this->assertCount(1, $discovered);
    }

    /** @test */
    public function it_can_use_multiple_paths_and_base_models()
    {
        $this->discoverer
            ->withBaseModels([BaseModel::class, AlternativeBaseModel::class])
            ->withPaths([__DIR__ . '/Fakes', __DIR__ . '/AlternativeFakes']);

        $this->assertEquals([
            'event' => EventModel::class,
            'general' => GeneralModel::class,
            'alternativeGeneral' => AlternativeGeneralModel::class,
        ], $this->discoverer->discover());
    }

    /** @test */
    public function it_will_handle_exceptions()
    {
        $this->expectException(MorphClassCouldNotBeResolved::class);
        $this->expectExceptionMessage("Could not get the morph class from: `Spatie\LaravelMorphMapGenerator\Tests\FailureFakes\ExceptionMorphClassModel`, it returned the following exception: Model `Spatie\LaravelMorphMapGenerator\Tests\FailureFakes\ExceptionMorphClassModel` has no morph class");

        $this->discoverer
            ->withBaseModels([BaseModel::class])
            ->withPaths([__DIR__ . '/FailureFakes'])
            ->ignoreModels([
                NullMorphClassModel::class,
            ]);

        $this->discoverer->discover();
    }

    /** @test */
    public function it_will_handle_empty_morph_classes()
    {
        $this->expectException(MorphClassCouldNotBeResolved::class);
        $this->expectExceptionMessage("Could not get the morph class from: `Spatie\LaravelMorphMapGenerator\Tests\FailureFakes\NullMorphClassModel`, it returned null");

        $this->discoverer
            ->withBaseModels([BaseModel::class])
            ->withPaths([__DIR__ . '/FailureFakes'])
            ->ignoreModels([
                ExceptionMorphClassModel::class,
            ]);

        $this->discoverer->discover();
    }

    /** @test */
    public function it_can_detect_that_a_morph_class_was_duplicated()
    {
        $this->expectException(DuplicateMorphClassFound::class);
        $this->expectExceptionMessage('The morph class used in: Spatie\LaravelMorphMapGenerator\Tests\FailureFakes\DuplicateModel` cannot be the same as the one used in: `Spatie\LaravelMorphMapGenerator\Tests\Fakes\GeneralModel`');

        $this->discoverer
            ->withBaseModels([BaseModel::class])
            ->withPaths([__DIR__ . '/Fakes', __DIR__ . '/FailureFakes'])
            ->ignoreModels([
                NullMorphClassModel::class,
                ExceptionMorphClassModel::class,
            ]);

        $this->discoverer->discover();
    }

    /** @test */
    public function it_will_filter_out_model_that_have_no_get_morph_class_method_implemented()
    {
        $this->discoverer
            ->withBaseModels([AlternativeBaseModel::class])
            ->withPaths([__DIR__ . '/AlternativeFakes']);

        $this->assertEquals([
            'alternativeGeneral' => AlternativeGeneralModel::class,
        ], $this->discoverer->discover());
    }

    /** @test */
    public function it_wont_discover_models_in_the_autoloaded_directory()
    {
        $this->discoverer
            ->withBaseModels([Model::class])
            ->withPaths([__DIR__ . '/../vendor']);

        $this->assertEmpty($this->discoverer->discover());
    }
}
