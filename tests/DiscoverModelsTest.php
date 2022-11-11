<?php

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelMorphMapGenerator\DiscoverModels;
use Spatie\LaravelMorphMapGenerator\Exceptions\DuplicateMorphClassFound;
use Spatie\LaravelMorphMapGenerator\Exceptions\MorphClassCouldNotBeResolved;
use Spatie\LaravelMorphMapGenerator\MorphMapGenerator;
use Spatie\LaravelMorphMapGenerator\Tests\AlternativeFakes\AlternativeBaseModel;
use Spatie\LaravelMorphMapGenerator\Tests\AlternativeFakes\AlternativeGeneralModel;
use Spatie\LaravelMorphMapGenerator\Tests\FailureFakes\ExceptionMorphClassModel;
use Spatie\LaravelMorphMapGenerator\Tests\FailureFakes\NullMorphClassModel;
use Spatie\LaravelMorphMapGenerator\Tests\Fakes\AbstractModel;
use Spatie\LaravelMorphMapGenerator\Tests\Fakes\BaseModel;
use Spatie\LaravelMorphMapGenerator\Tests\Fakes\EventModel;
use Spatie\LaravelMorphMapGenerator\Tests\Fakes\GeneralModel;
use Spatie\LaravelMorphMapGenerator\Tests\Fakes\OtherTypeModel;

beforeEach(function () {
    $this->discoverer = DiscoverModels::create()
        ->withBasePath(realpath(__DIR__ . '/../'))
        ->withRootNamespace('Spatie\LaravelMorphMapGenerator\\');

    $this->generator = MorphMapGenerator::create();
});

it('will discover models in a path', function () {
    $this->discoverer
        ->withBaseModels([BaseModel::class])
        ->withPaths([__DIR__ . '/Fakes']);

    $generated = $this->generator->generate(
        $this->discoverer->discover()
    );

    expect($generated)
        ->toEqual([
            'event' => EventModel::class,
            'general' => GeneralModel::class,
        ])
        ->not->toContain([
            'abstract' => AbstractModel::class,
        ])
        ->not->toContain([
            'other_type' => OtherTypeModel::class,
        ]);
});

it('can ignore models', function () {
    $this->discoverer
        ->withBaseModels([BaseModel::class])
        ->withPaths([__DIR__ . '/Fakes'])
        ->ignoreModels([
            EventModel::class,
        ]);

    $generated = $this->generator->generate(
        $this->discoverer->discover()
    );

    expect($generated)
        ->not->toHaveKey('event')
        ->toHaveCount(1);
});

it('can use multiple paths and base models', function () {
    $this->discoverer
        ->withBaseModels([BaseModel::class, AlternativeBaseModel::class])
        ->withPaths([__DIR__ . '/Fakes', __DIR__ . '/AlternativeFakes']);

    $generated = $this->generator->generate(
        $this->discoverer->discover()
    );

    expect($generated)->toEqual([
        'event' => EventModel::class,
        'general' => GeneralModel::class,
        'alternativeGeneral' => AlternativeGeneralModel::class,
    ]);
});

it('will handle exceptions', function () {
    $this->discoverer
        ->withBaseModels([BaseModel::class])
        ->withPaths([__DIR__ . '/FailureFakes'])
        ->ignoreModels([
            NullMorphClassModel::class,
        ]);

    $this->generator->generate(
        $this->discoverer->discover()
    );
})->throws(
    MorphClassCouldNotBeResolved::class,
    "Could not get the morph class from: `Spatie\LaravelMorphMapGenerator\Tests\FailureFakes\ExceptionMorphClassModel`, it returned the following exception: Model `Spatie\LaravelMorphMapGenerator\Tests\FailureFakes\ExceptionMorphClassModel` has no morph class"
);

it('will handle empty classes', function () {
    $this->discoverer
        ->withBaseModels([BaseModel::class])
        ->withPaths([__DIR__ . '/FailureFakes'])
        ->ignoreModels([
            ExceptionMorphClassModel::class,
        ]);

    $this->generator->generate(
        $this->discoverer->discover()
    );
})->throws(
    MorphClassCouldNotBeResolved::class,
    "Could not get the morph class from: `Spatie\LaravelMorphMapGenerator\Tests\FailureFakes\NullMorphClassModel`, it returned null"
);

it('can detect that a morph class was duplicated', function () {
    $this->discoverer
        ->withBaseModels([BaseModel::class])
        ->withPaths([__DIR__ . '/Fakes', __DIR__ . '/FailureFakes'])
        ->ignoreModels([
            NullMorphClassModel::class,
            ExceptionMorphClassModel::class,
        ]);

    $this->generator->generate(
        $this->discoverer->discover()
    );
})->throws(
    DuplicateMorphClassFound::class,
    'The morph class used in: Spatie\LaravelMorphMapGenerator\Tests\FailureFakes\DuplicateModel` cannot be the same as the one used in: `Spatie\LaravelMorphMapGenerator\Tests\Fakes\GeneralModel`'
);

it('will filter out models that have no get morph class method implemented', function () {
    $this->discoverer
        ->withBaseModels([AlternativeBaseModel::class])
        ->withPaths([__DIR__ . '/AlternativeFakes']);

    $generated = $this->generator->generate(
        $this->discoverer->discover()
    );

    expect($generated)->toEqual([
        'alternativeGeneral' => AlternativeGeneralModel::class,
    ]);
});

it("won't discover models in the autoloaded directory", function () {
    $this->discoverer
        ->withBaseModels([Model::class])
        ->withPaths([__DIR__ . '/../vendor']);

    $generated = $this->generator->generate(
        $this->discoverer->discover()
    );

    expect($generated)->toBeEmpty();
});
