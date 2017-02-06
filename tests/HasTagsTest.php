<?php

namespace Spatie\Translatable\Test;

use Spatie\Tags\Tag;
use Spatie\Tags\Test\TestCase;
use Spatie\Tags\Test\TestModel;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class HasTagsTest extends TestCase
{
    /** @var \Spatie\Tags\Test\TestModel */
    protected $testModel;

    public function setUp()
    {
        parent::setUp();

        $this->testModel = TestModel::create(['name' => 'default']);
    }

    /** @test */
    public function it_provides_a_tags_relation()
    {
        $this->assertInstanceOf(MorphToMany::class, $this->testModel->tags());
    }

    /** @test */
    public function it_can_attach_a_tag()
    {
        $this->testModel->attachTag('tagName');

        $this->assertCount(1, $this->testModel->tags);

        $this->assertEquals(['tagName'], $this->testModel->tags->pluck('name')->toArray());
    }

    /** @test */
    public function it_can_use_a_tag_model_when_attaching_a_tag()
    {
        $tag = Tag::findOrCreate('tagName');

        $this->testModel->attachTag($tag);

        $this->assertEquals(['tagName'], $this->testModel->tags->pluck('name')->toArray());
    }

    /** @test */
    public function it_can_attach_a_tag_inside_a_static_create_method()
    {
        $testModel = TestModel::create([
            'name' => 'testModel',
            'tags' => ['tag', 'tag2'],
        ]);

        $this->assertCount(2, $testModel->tags);
    }

    /** @test */
    public function it_can_attach_a_tag_via_the_tags_mutator()
    {
        $this->testModel->tags = ['tag1'];

        $this->assertCount(1, $this->testModel->tags);
    }

    /** @test */
    public function it_can_attach_multiple_tags_via_the_tags_mutator()
    {
        $this->testModel->tags = ['tag1', 'tag2'];

        $this->assertCount(2, $this->testModel->tags);
    }

    /** @test */
    public function it_can_attach_multiple_tags()
    {
        $this->testModel->attachTags(['test1', 'test2']);

        $this->assertCount(2, $this->testModel->tags);
    }

    /** @test */
    public function it_can_attach_a_existing_tag()
    {
        $this->testModel->attachTag(Tag::findOrCreate('test'));

        $this->assertCount(1, $this->testModel->tags);
    }

    /** @test */
    public function it_can_detach_a_tag()
    {
        $this->testModel->attachTags(['test1', 'test2', 'test3']);

        $this->testModel->detachTag('test2');

        $this->assertEquals(['test1', 'test3'], $this->testModel->tags->pluck('name')->toArray());
    }

    /** @test */
    public function it_can_detach_multiple_tags()
    {
        $this->testModel->attachTags(['test1', 'test2', 'test3']);

        $this->testModel->detachTags(['test1', 'test3']);

        $this->assertEquals(['test2'], $this->testModel->tags->pluck('name')->toArray());
    }

    /** @test */
    public function it_can_get_all_attached_tags_of_a_certain_type()
    {
        $this->testModel->tags()->attach(Tag::findOrCreate('test', 'type1'));
        $this->testModel->tags()->attach(Tag::findOrCreate('test2', 'type2'));

        $tagsOfType2 = $this->testModel->tagsWithType('type2');

        $this->assertCount(1, $tagsOfType2);
        $this->assertEquals('type2', $tagsOfType2->first()->type);
    }

    /** @test */
    public function it_provides_as_scope_to_get_all_models_that_have_any_of_the_given_tags_2()
    {
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

        $this->assertEquals(['model2', 'model3'], $testModels->pluck('name')->toArray());
    }

    /** @test */
    public function it_provides_a_scope_to_get_all_models_that_have_any_of_the_given_tags_without_specifying_type()
    {
        TestModel::create([
            'name' => 'model1'
        ])->tags()->attach(Tag::findOrCreate('test1', 'type1'));

        TestModel::create([
            'name' => 'model2'
        ])->tags()->attach(Tag::findOrCreate('test2', 'type2'));

        $testModels = TestModel::withAnyTags(['test1', 'test2']);

        $this->assertEquals(['model1', 'model2'], $testModels->pluck('name')->toArray());
    }

    /** @test */
    public function it_provides_a_scope_to_get_all_models_that_have_all_of_the_given_tags_without_specifying_type()
    {
        $model1 = TestModel::create([
            'name' => 'model1'
        ]);
        $model1->tags()->attach(Tag::findOrCreate('test1', 'type1'));
        $model1->tags()->attach(Tag::findOrCreate('test2', 'type1'));

        $testModels = TestModel::withAllTags(['test1', 'test2']);

        $this->assertEquals(['model1'], $testModels->pluck('name')->toArray());
    }

    /** @test */
    public function it_can_sync_a_single_tag()
    {
        $this->testModel->attachTags(['tag1', 'tag2', 'tag3']);

        $this->testModel->syncTags(['tag3']);

        $this->assertEquals(['tag3'], $this->testModel->tags->pluck('name')->toArray());
    }

    /** @test */
    public function it_can_sync_multiple_tags()
    {
        $this->testModel->attachTags(['tag1', 'tag2', 'tag3']);

        $this->testModel->syncTags(['tag3', 'tag4']);

        $this->assertEquals(['tag3', 'tag4'], $this->testModel->tags->pluck('name')->toArray());
    }
}
