<?php

use Spatie\Tags\Tag;
use Spatie\Tags\Test\TestClasses\TestModel;

beforeEach(function () {
    TestModel::create([
        'name' => 'model1',
        'tags' => ['tagA'],
    ]);

    TestModel::create([
        'name' => 'model2',
        'tags' => ['tagA', 'tagB'],
    ]);

    TestModel::create([
        'name' => 'model3',
        'tags' => ['tagA', 'tagB', 'tagC'],
    ]);

    TestModel::create([
        'name' => 'model4',
        'tags' => ['tagD'],
    ]);

    $typedTag = Tag::findOrCreate('tagE', 'typedTag');
    $anotherTypedTag = Tag::findOrCreate('tagF', 'typedTag');
    $tagWithSecondType = Tag::findOrCreate('tagG', 'secondType');

    TestModel::create([
        'name' => 'model5',
        'tags' => [$typedTag, $anotherTypedTag],
    ]);

    TestModel::create([
        'name' => 'model6',
        'tags' => [$typedTag],
    ]);

    TestModel::create([
        'name' => 'model7',
        'tags' => [$typedTag, $tagWithSecondType],
    ]);
});

it('provides as scope to get all models that have any tags of the given types', function () {
    // String input
    $testModels = TestModel::withAnyTagsOfType('typedTag')->get();

    expect($testModels->pluck('name')
        ->toArray())
        ->toContain('model5', 'model6')
        ->not->toContain('model3', 'model7');

    // Array input
    $testModels = TestModel::withAnyTagsOfType(['typedTag', 'secondType'])->get();

    expect($testModels->pluck('name')
        ->toArray())
        ->toContain('model5', 'model6', 'model7')
        ->not->toContain('model3');
});

it('provides a scope to get all models that have any of the given tags', function () {
    $testModels = TestModel::withAnyTags(['tagC', 'tagD'])->get();

    expect($testModels->pluck('name')->toArray())->toContain('model3', 'model4');
});


test('the with any tags scopes will still items when passing a non existing tag', function () {
    $testModels = TestModel::withAnyTags(['tagB', 'tagC', 'nonExistingTag'])->get();

    expect($testModels->pluck('name')->toArray())->toContain('model2', 'model3');
});


it('provides a scope to get all models that have all of the given tags', function () {
    $testModels = TestModel::withAllTags(['tagA', 'tagB'])->get();

    expect($testModels->pluck('name')->toArray())->toEqual(['model2', 'model3']);

    $testModels = TestModel::withAllTags(['tagB', 'tagC'])->get();

    expect($testModels->pluck('name')->toArray())->toEqual(['model3']);
});


it('provides a scope to get all models that have the given tag instance', function () {
    $tagModel = Tag::findOrCreate('tagB');

    $testModels = TestModel::withAllTags($tagModel)->get();

    expect($testModels->pluck('name')->toArray())->toEqual(['model2', 'model3']);
});

it('provides a scope to get all models from a slug of a tag', function () {
    $tagModel = Tag::findOrCreate('tag B');

    $testModels = TestModel::create([
        'name' => 'model7',
        'tags' => ['tag B'],
    ]);

    $testModels = TestModel::withAllTags([$tagModel->slug])->get();

    expect($testModels->pluck('name')->toArray())->toEqual(['model7']);
    expect($testModels->first()->tags->pluck('name')->toArray())->toEqual(['tag B']);
    expect($testModels->first()->tags->pluck('slug')->toArray())->toEqual(['tag-b']);
});

it('returns tags attached to a model in the correct order', function () {
    $tag = Tag::findOrCreate('string');
    $tag->order_column = 10;
    $tag->save();

    $tag2 = Tag::findOrCreate('string 2');
    $tag2->order_column = 20;
    $tag2->save();

    $model = TestModel::create(['name' => 'test']);
    $model->attachTags([$tag2, $tag]);

    expect($model->tags->pluck('name')->toArray())->toEqual(['string', 'string 2']);
    expect($model->tags->pluck('order_column')->toArray())->toEqual([10, 20]);

    $foundModelAnyOrder = TestModel::withAnyTags(['string', 'string-2'])->first();
    expect($foundModelAnyOrder->tags->pluck('name')->toArray())->toEqual(['string', 'string 2']);
    expect($foundModelAnyOrder->tags->pluck('order_column')->toArray())->toEqual([10, 20]);
});


it('provides a scope to get all models that have any of the given tags with type', function () {
    $testModels = TestModel::withAnyTags(['tagE'], 'typedTag')->get();

    expect($testModels->pluck('name')->toArray())->toContain('model5', 'model6');

    $testModels = TestModel::withAnyTags(['tagF'], 'typedTag')->get();

    expect($testModels->pluck('name')->toArray())->toEqual(['model5']);

    $testModels = TestModel::withAnyTags(['tagF'])->get();

    expect($testModels->pluck('name')->toArray())->toEqual([]);
});


it('provides a scope to get all models that have all of the given tags with type', function () {
    $testModels = TestModel::withAllTags(['tagE', 'tagF'], 'typedTag')->get();

    expect($testModels->pluck('name')->toArray())->toEqual(['model5']);
});


it('provides a scope to get all models that have any of the given tags with any type', function () {
    $testModels = TestModel::withAnyTagsOfAnyType(['tagE', 'tagF'])->get();

    expect($testModels->pluck('name')->toArray())->toContain('model5', 'model6');
});


it('provides a scope to get all models that have any of the given tags with any type from mixed tag values', function () {
    $tagD = Tag::findFromString('tagD');

    $testModels = TestModel::withAnyTagsOfAnyType([$tagD, 'tagE', 'tagF'])->get();

    expect($testModels->pluck('name')->toArray())->toContain('model4', 'model5', 'model6');
});


it('provides a scope to get all models that have all of the given tags with any type', function () {
    $testModels = TestModel::withAllTagsOfAnyType(['tagE', 'tagF'])->get();

    expect($testModels->pluck('name')->toArray())->toEqual(['model5']);
});


it('provides a scope to get all models that have all of the given tags with any type from mixed tag values', function () {
    $tagE = Tag::findFromString('tagE', 'typedTag');

    $testModels = TestModel::withAllTagsOfAnyType([$tagE, 'tagF'])->get();

    expect($testModels->pluck('name')->toArray())->toEqual(['model5']);
});
