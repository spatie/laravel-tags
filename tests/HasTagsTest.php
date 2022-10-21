<?php

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Tags\Tag;
use Spatie\Tags\Test\TestClasses\TestAnotherModel;
use Spatie\Tags\Test\TestClasses\TestModel;

beforeEach(function () {
    $this->testModel = TestModel::create(['name'=> 'default']);
 });


it('provides a tags relation',function()
{
    $this->assertInstanceOf(MorphToMany::class, $this->testModel->tags());
});


it('can attach a tag',function()
{
    $this->testModel->attachTag('tagName');

    $this->assertCount(1, $this->testModel->tags);

    $this->assertEquals(['tagName'], $this->testModel->tags->pluck('name')->toArray());
 });

it('can attach a tag with a type',function()
{
    $this->testModel->attachTag('tagName', 'testType');

    $this->assertCount(1, $this->testModel->tags);

    $this->assertEquals(['tagName'], $this->testModel->tags->pluck('name')->toArray());

    $this->assertEquals(['testType'], $this->testModel->tags->pluck('type')->toArray());
 });


it('can attach a tag multiple times without creating duplicate entries',function()
{
    $this->testModel->attachTag('tagName');
    $this->testModel->attachTag('tagName');

    $this->assertCount(1, $this->testModel->tags);
 });


it('can use a tag model when attaching a tag',function()
{
    $tag = Tag::findOrCreate('tagName');

    $this->testModel->attachTag($tag);

    $this->assertEquals(['tagName'], $this->testModel->tags->pluck('name')->toArray());
 });


it('can attach a tag inside a static create method',function()
{
    $testModel = TestModel::create([
        'name'=> 'testModel',
        'tags'=> ['tag', 'tag2'],
    ]);

    $this->assertCount(2, $testModel->tags);
 });


it('can attach a tag via the tags mutator',function()
{
    $this->testModel->tags = 'tag1';

    $this->assertCount(1, $this->testModel->tags);
 });


it('can attach multiple tags via the tags mutator',function()
{
    $this->testModel->tags = ['tag1', 'tag2'];

    $this->assertCount(2, $this->testModel->tags);
 });


it('can override tags via the tags mutator',function()
{
    $this->testModel->tags = ['tag1', 'tag2'];
    $this->testModel->tags = ['tag2', 'tag3', 'tag4'];

    $this->assertCount(3, $this->testModel->tags);
 });


it('can attach multiple tags',function()
{
    $this->testModel->attachTags(['test1', 'test2']);

    $this->assertCount(2, $this->testModel->tags);
 });


it('can attach multiple tags with a type',function()
{
    $this->testModel->attachTags(['test1', 'test2'], 'testType');

    $this->assertCount(2, $this->testModel->tags->where('type', '=', 'testType')->toArray());
 });


it('can attach a existing tag',function()
{
    $this->testModel->attachTag(Tag::findOrCreate('test'));

    $this->assertCount(1, $this->testModel->tags);
 });


it('can detach a tag',function()
{
    $this->testModel->attachTags(['test1', 'test2', 'test3']);

    $this->testModel->detachTag('test2');

    $this->assertEquals(['test1', 'test3'], $this->testModel->tags->pluck('name')->toArray());
 });


it('can detach a tag with a type',function()
{
    $this->testModel->attachTags(['test1', 'test2'], 'testType');

    $this->testModel->detachTag('test2', 'testType');

    $this->assertEquals(['test1'], $this->testModel->tags->pluck('name')->toArray());
 });


it('can detach a tag with a type and not affect a tag without a type',function()
{
    $this->testModel->attachTag('test1', 'testType');

    $this->testModel->attachTag('test1');

    $this->testModel->detachTag('test1', 'testType');

    $this->assertEquals(['test1'], $this->testModel->tags->pluck('name')->toArray());

    $this->assertNull($this->testModel->tags->where('name', '=', 'test1')->first()->type);
 });


it('can detach a tag with a type while leaving another of a different type',function()
{
    $this->testModel->attachTag('test1', 'testType');

    $this->testModel->attachTag('test1', 'otherType');

    $this->testModel->detachTag('test1', 'testType');

    $this->assertEquals(['test1'], $this->testModel->tags->pluck('name')->sort()->toArray());

    $this->assertEquals('otherType', $this->testModel->tags->where('name', '=', 'test1')->first()->type);
 });


it('can detach multiple tags',function()
{
    $this->testModel->attachTags(['test1', 'test2', 'test3']);

    $this->testModel->detachTags(['test1', 'test3']);

    $this->assertEquals(['test2'], $this->testModel->tags->pluck('name')->toArray());
 });


it('can get all attached tags of a certain type',function()
{
    $this->testModel->tags()->attach(Tag::findOrCreate('test', 'type1'));
    $this->testModel->tags()->attach(Tag::findOrCreate('test2', 'type2'));

    $tagsOfType2 = $this->testModel->tagsWithType('type2');

    $this->assertCount(1, $tagsOfType2);
    $this->assertEquals('type2', $tagsOfType2->first()->type);
 });


it('provides a scope to get all models that have any of the given tags 2',function()
{
    TestModel::create([
        'name'=> 'model1',
        'tags'=> ['tagA'],
    ]);

    TestModel::create([
        'name'=> 'model2',
        'tags'=> ['tagA', 'tagB'],
    ]);

    TestModel::create([
        'name'=> 'model3',
        'tags'=> ['tagA', 'tagB', 'tagC'],
    ]);

    $testModels = TestModel::withAnyTags(['tagB', 'tagC']);

    $this->assertEquals(['model2', 'model3'], $testModels->pluck('name')->toArray());
 });


it('provides a scope to get all models that have a given tag',function()
{
    TestModel::create([
        'name'=> 'model1',
        'tags'=> ['tagA'],
    ]);

    TestModel::create([
        'name'=> 'model2',
        'tags'=> ['tagA', 'tagB'],
    ]);

    TestModel::create([
        'name'=> 'model3',
        'tags'=> ['tagA', 'tagB', 'tagC'],
    ]);

    $testModels = TestModel::withAnyTags('tagB');

    $this->assertEquals(['model2', 'model3'], $testModels->pluck('name')->toArray());

    $testModels = TestModel::withAllTags('tagB');

    $this->assertEquals(['model2', 'model3'], $testModels->pluck('name')->toArray());
 });


it('provides a scope to get all models that have all given tags',function()
{
    TestModel::create([
        'name'=> 'model1',
        'tags'=> ['tagA'],
    ]);

    TestModel::create([
        'name'=> 'model2',
        'tags'=> ['tagA', 'tagB'],
    ]);

    TestModel::create([
        'name'=> 'model3',
        'tags'=> ['tagA', 'tagB', 'tagC'],
    ]);

    $testModels = TestModel::withAllTags(['tagB', 'tagC']);

    $this->assertEquals(['model3'], $testModels->pluck('name')->toArray());
 });


it('provides a scope to get all models that have any of the given tag instances',function()
{
    $tag = Tag::findOrCreate('tagA', 'typeA');

    TestModel::create([
        'name'=> 'model1',
    ])->attachTag($tag);

    $testModels = TestModel::withAnyTags([$tag]);

    $this->assertEquals(['model1'], $testModels->pluck('name')->toArray());
 });


it('can sync a single tag',function()
{
    $this->testModel->attachTags(['tag1', 'tag2', 'tag3']);

    $this->testModel->syncTags('tag3');

    $this->assertEquals(['tag3'], $this->testModel->tags->pluck('name')->toArray());
 });


it('can sync multiple tags',function()
{
    $this->testModel->attachTags(['tag1', 'tag2', 'tag3']);

    $this->testModel->syncTags(['tag3', 'tag4']);

    $this->assertEquals(['tag3', 'tag4'], $this->testModel->tags->pluck('name')->toArray());
 });


it('can sync tags with different types',function()
{
    $this->testModel->syncTagsWithType(['tagA1', 'tagA2', 'tagA3'], 'typeA');
    $this->testModel->syncTagsWithType(['tagB1', 'tagB2'], 'typeB');

    $tagsOfTypeA = $this->testModel->tagsWithType('typeA');
    $this->assertEquals(['tagA1', 'tagA2', 'tagA3'], $tagsOfTypeA->pluck('name')->toArray());

    $tagsOfTypeB = $this->testModel->tagsWithType('typeB');
    $this->assertEquals(['tagB1', 'tagB2'], $tagsOfTypeB->pluck('name')->toArray());
 });


it('can sync same tag type with different models with same foreign id',function()
{
    $this->testModel->syncTagsWithType(['tagA1', 'tagA2', 'tagA3'], 'typeA');

    $testAnotherModel = TestAnotherModel::create([
        'name'=> 'model2',
    ])->syncTagsWithType(['tagA1'], 'typeA');

    // They should have the same foreign ID in taggables table
    $this->assertEquals('1', $this->testModel->id);
    $this->assertEquals('1', $testAnotherModel->id);

    $testAnotherModelTagsOfTypeA = $testAnotherModel->tagsWithType('typeA');
    $this->assertEquals(['tagA1'], $testAnotherModelTagsOfTypeA->pluck('name')->toArray());
 });


it('can detach tags on model delete',function()
{
    $this->testModel->attachTag('tagDeletable');

    $this->testModel->delete();

    $this->assertEquals(0, $this->testModel->tags()->get()->count());
 });


it('can delete models without tags',function()
{
    $this->assertTrue($this->testModel->delete());
 });


it('can sync tags with same name',function()
{
    $this->testModel->syncTagsWithType(['tagA1', 'tagA1'], 'typeA');

    $tagsOfTypeA = $this->testModel->tagsWithType('typeA');
    $this->assertEquals(['tagA1'], $tagsOfTypeA->pluck('name')->toArray());
 });
