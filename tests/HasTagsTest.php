<?php

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Tags\Tag;
use Spatie\Tags\Test\TestClasses\TestAnotherModel;
use Spatie\Tags\Test\TestClasses\TestModel;

beforeEach(function () {
    $this->testModel = TestModel::create(['name' => 'default']);
});


it('provides a tags relation', function () {
    expect($this->testModel->tags())->toBeInstanceOf(MorphToMany::class);
});


it('can attach a tag', function () {
    $this->testModel->attachTag('tagName');

    expect($this->testModel->tags)->toHaveCount(1);

    expect($this->testModel->tags->pluck('name')->toArray())->toEqual(['tagName']);
});

it('can attach a tag with a type', function () {
    $this->testModel->attachTag('tagName', 'testType');

    expect($this->testModel->tags)->toHaveCount(1);

    expect($this->testModel->tags->pluck('name')->toArray())->toEqual(['tagName']);

    expect($this->testModel->tags->pluck('type')->toArray())->toEqual(['testType']);
});


it('can attach a tag multiple times without creating duplicate entries', function () {
    $this->testModel->attachTag('tagName');
    $this->testModel->attachTag('tagName');

    expect($this->testModel->tags)->toHaveCount(1);
});


it('can use a tag model when attaching a tag', function () {
    $tag = Tag::findOrCreate('tagName');

    $this->testModel->attachTag($tag);

    expect($this->testModel->tags->pluck('name')->toArray())->toEqual(['tagName']);
});


it('can attach a tag inside a static create method', function () {
    $testModel = TestModel::create([
        'name' => 'testModel',
        'tags' => ['tag', 'tag2'],
    ]);

    expect($testModel->tags)->toHaveCount(2);
});


it('can attach a tag via the tags mutator', function () {
    $this->testModel->tags = 'tag1';

    expect($this->testModel->tags)->toHaveCount(1);
});


it('can attach multiple tags via the tags mutator', function () {
    $this->testModel->tags = ['tag1', 'tag2'];

    expect($this->testModel->tags)->toHaveCount(2);
});


it('can override tags via the tags mutator', function () {
    $this->testModel->tags = ['tag1', 'tag2'];
    $this->testModel->tags = ['tag2', 'tag3', 'tag4'];

    expect($this->testModel->tags)->toHaveCount(3);
});


it('can attach multiple tags', function () {
    $this->testModel->attachTags(['test1', 'test2']);

    expect($this->testModel->tags)->toHaveCount(2);
});


it('can attach multiple tags with a type', function () {
    $this->testModel->attachTags(['test1', 'test2'], 'testType');

    expect($this->testModel->tags->where('type', '=', 'testType')->toArray())->toHaveCount(2);
});


it('can attach a existing tag', function () {
    $this->testModel->attachTag(Tag::findOrCreate('test'));

    expect($this->testModel->tags)->toHaveCount(1);
});


it('can detach a tag', function () {
    $this->testModel->attachTags(['test1', 'test2', 'test3']);

    $this->testModel->detachTag('test2');

    expect($this->testModel->tags->pluck('name')->toArray())->toEqual(['test1', 'test3']);
});


it('can detach a tag with a type', function () {
    $this->testModel->attachTags(['test1', 'test2'], 'testType');

    $this->testModel->detachTag('test2', 'testType');

    expect($this->testModel->tags->pluck('name')->toArray())->toEqual(['test1']);
});


it('can detach a tag with a type and not affect a tag without a type', function () {
    $this->testModel->attachTag('test1', 'testType');

    $this->testModel->attachTag('test1');

    $this->testModel->detachTag('test1', 'testType');

    expect($this->testModel->tags->pluck('name')->toArray())->toEqual(['test1']);

    expect($this->testModel->tags->where('name', '=', 'test1')->first()->type)->toBeNull();
});


it('can detach a tag with a type while leaving another of a different type', function () {
    $this->testModel->attachTag('test1', 'testType');

    $this->testModel->attachTag('test1', 'otherType');

    $this->testModel->detachTag('test1', 'testType');

    expect($this->testModel->tags->pluck('name')->sort()->toArray())->toEqual(['test1']);

    expect($this->testModel->tags->where('name', '=', 'test1')->first()->type)->toBe('otherType');
});


it('can detach multiple tags', function () {
    $this->testModel->attachTags(['test1', 'test2', 'test3']);

    $this->testModel->detachTags(['test1', 'test3']);

    expect($this->testModel->tags->pluck('name')->toArray())->toEqual(['test2']);
});


it('can get all attached tags of a certain type', function () {
    $this->testModel->tags()->attach(Tag::findOrCreate('test', 'type1'));
    $this->testModel->tags()->attach(Tag::findOrCreate('test2', 'type2'));

    $tagsOfType2 = $this->testModel->tagsWithType('type2');

    expect($tagsOfType2)->toHaveCount(1);
    expect($tagsOfType2->first()->type)->toBe('type2');
});


it('provides a scope to get all models that have any of the given tags 2', function () {
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

    $testModels = TestModel::withAnyTags(['tagB', 'tagC']);

    expect($testModels->pluck('name')->toArray())->toEqual(['model2', 'model3']);
});


it('provides a scope to get all models that have a given tag', function () {
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

    $testModels = TestModel::withAnyTags('tagB');

    expect($testModels->pluck('name')->toArray())->toEqual(['model2', 'model3']);

    $testModels = TestModel::withAllTags('tagB');

    expect($testModels->pluck('name')->toArray())->toEqual(['model2', 'model3']);
});


it('provides a scope to get all models that have all given tags', function () {
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

    $testModels = TestModel::withAllTags(['tagB', 'tagC']);

    expect($testModels->pluck('name')->toArray())->toEqual(['model3']);
});


it('provides a scope to get all models that do not have any of the given tags', function () {
    $this->testModel->attachTag('tagA');

    TestModel::create([
        'name' => 'model1',
        'tags' => ['tagA', 'tagB'],
    ]);

    TestModel::create([
        'name' => 'model2',
        'tags' => ['tagA', 'tagB', 'tagC'],
    ]);

    $testModels = TestModel::withoutTags(['tagC']);

    expect($testModels->pluck('name')->toArray())->toEqual(['default', 'model1']);

    $testModels = TestModel::withoutTags(['tagC', 'tagB']);

    expect($testModels->pluck('name')->toArray())->toEqual(['default']);
});


it('provides a scope to get all models that have any of the given tag instances', function () {
    $tag = Tag::findOrCreate('tagA', 'typeA');

    TestModel::create([
        'name' => 'model1',
    ])->attachTag($tag);

    $testModels = TestModel::withAnyTags([$tag]);

    expect($testModels->pluck('name')->toArray())->toEqual(['model1']);
});


it('can sync a single tag', function () {
    $this->testModel->attachTags(['tag1', 'tag2', 'tag3']);

    $this->testModel->syncTags('tag3');

    expect($this->testModel->tags->pluck('name')->toArray())->toEqual(['tag3']);
});


it('can sync multiple tags', function () {
    $this->testModel->attachTags(['tag1', 'tag2', 'tag3']);

    $this->testModel->syncTags(['tag3', 'tag4']);

    expect($this->testModel->tags->pluck('name')->toArray())->toEqual(['tag3', 'tag4']);
});


it('can sync multiple tags from a collection', function () {
    $this->testModel->attachTags(collect(['tag1', 'tag2', 'tag3']));

    $this->testModel->syncTags(collect(['tag3', 'tag4']));

    expect($this->testModel->tags->pluck('name')->toArray())->toEqual(['tag3', 'tag4']);
});


it('can sync tags with different types', function () {
    $this->testModel->syncTagsWithType(['tagA1', 'tagA2', 'tagA3'], 'typeA');
    $this->testModel->syncTagsWithType(['tagB1', 'tagB2'], 'typeB');

    $tagsOfTypeA = $this->testModel->tagsWithType('typeA');
    expect($tagsOfTypeA->pluck('name')->toArray())->toEqual(['tagA1', 'tagA2', 'tagA3']);

    $tagsOfTypeB = $this->testModel->tagsWithType('typeB');
    expect($tagsOfTypeB->pluck('name')->toArray())->toEqual(['tagB1', 'tagB2']);
});


it('can sync same tag type with different models with same foreign id', function () {
    $this->testModel->syncTagsWithType(['tagA1', 'tagA2', 'tagA3'], 'typeA');

    $testAnotherModel = TestAnotherModel::create([
        'name' => 'model2',
    ])->syncTagsWithType(['tagA1'], 'typeA');

    // They should have the same foreign ID in taggables table
    expect($this->testModel->id)->toBe(1);
    expect($testAnotherModel->id)->toBe(1);

    $testAnotherModelTagsOfTypeA = $testAnotherModel->tagsWithType('typeA');
    expect($testAnotherModelTagsOfTypeA->pluck('name')->toArray())->toEqual(['tagA1']);
});


it('can detach tags on model delete', function () {
    $this->testModel->attachTag('tagDeletable');

    $this->testModel->delete();

    expect($this->testModel->tags()->get())->toHaveCount(0);
});


it('can delete models without tags', function () {
    expect($this->testModel->delete())->toBeTrue();
});


it('can sync tags with same name', function () {
    $this->testModel->syncTagsWithType(['tagA1', 'tagA1'], 'typeA');

    $tagsOfTypeA = $this->testModel->tagsWithType('typeA');
    expect($tagsOfTypeA->pluck('name')->toArray())->toEqual(['tagA1']);
});
