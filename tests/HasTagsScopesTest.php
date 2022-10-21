<?php

use Spatie\Tags\Tag;
use Spatie\Tags\Test\TestCase;
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

    TestModel::create([
        'name' => 'model5',
        'tags' => [$typedTag, $anotherTypedTag],
    ]);

    TestModel::create([
        'name' => 'model6',
        'tags' => [$typedTag],
    ]);
});


it('provides a scope to get all models that have any of the given tags',function()
{
    $testModels = TestModel::withAnyTags(['tagC', 'tagD'])->get();

    $this->assertEquals(['model3', 'model4'], $testModels->pluck('name')->toArray());
});


test('the with any tags scopes will still items when passing a non existing tag',function()
{
    $testModels = TestModel::withAnyTags(['tagB', 'tagC', 'nonExistingTag'])->get();

    $this->assertEquals(['model2', 'model3'], $testModels->pluck('name')->toArray());
});


it('provides a scope to get all models that have all of the given tags',function()
{
    $testModels = TestModel::withAllTags(['tagA', 'tagB'])->get();

    $this->assertEquals(['model2', 'model3'], $testModels->pluck('name')->toArray());

    $testModels = TestModel::withAllTags(['tagB', 'tagC'])->get();

    $this->assertEquals(['model3'], $testModels->pluck('name')->toArray());
});


it('provides a scope to get all models that have the given tag instance',function()
{
    $tagModel = Tag::findOrCreate('tagB');

    $testModels = TestModel::withAllTags($tagModel)->get();

    $this->assertEquals(['model2', 'model3'], $testModels->pluck('name')->toArray());
});


it('provides a scope to get all models that have any of the given tags with type',function()
{
    $testModels = TestModel::withAnyTags(['tagE'], 'typedTag')->get();

    $this->assertEquals(['model5', 'model6'], $testModels->pluck('name')->toArray());

    $testModels = TestModel::withAnyTags(['tagF'], 'typedTag')->get();

    $this->assertEquals(['model5'], $testModels->pluck('name')->toArray());

    $testModels = TestModel::withAnyTags(['tagF'])->get();

    $this->assertEquals([], $testModels->pluck('name')->toArray());
});


it('provides a scope to get all models that have all of the given tags with type',function()
{
    $testModels = TestModel::withAllTags(['tagE', 'tagF'], 'typedTag')->get();

    $this->assertEquals(['model5'], $testModels->pluck('name')->toArray());
});


it('provides a scope to get all models that have any of the given tags with any type',function()
{
    $testModels = TestModel::withAnyTagsOfAnyType(['tagE', 'tagF'])->get();

    $this->assertEquals(['model5', 'model6'], $testModels->pluck('name')->toArray());
});


it('provides a scope to get all models that have any of the given tags with any type from mixed tag values',function()
{
    $tagD = Tag::findFromString('tagD');

    $testModels = TestModel::withAnyTagsOfAnyType([$tagD, 'tagE', 'tagF'])->get();

    $this->assertEquals(['model4', 'model5', 'model6'], $testModels->pluck('name')->toArray());
});


it('provides a scope to get all models that have all of the given tags with any type',function()
{
    $testModels = TestModel::withAllTagsOfAnyType(['tagE', 'tagF'])->get();

    $this->assertEquals(['model5'], $testModels->pluck('name')->toArray());
});


it('provides a scope to get all models that have all of the given tags with any type from mixed tag values',function()
{
    $tagE = Tag::findFromString('tagE', 'typedTag');

    $testModels = TestModel::withAllTagsOfAnyType([$tagE, 'tagF'])->get();

    $this->assertEquals(['model5'], $testModels->pluck('name')->toArray());
});
