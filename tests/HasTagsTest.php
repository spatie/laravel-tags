<?php

namespace Spatie\Translatable\Test;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Tags\Tag;
use Spatie\Tags\Test\TestCase;
use Spatie\Tags\Test\TestModel;

class HasTagsText extends TestCase
{
    /** @var \Spatie\Tags\Test\TestModel  */
    protected $testModel;

    public function setUp()
    {
        parent::setUp();

        $this->testModel = TestModel::create([]);
    }

    /** @test */
    public function it_provides_a_tags_relation()
    {
        $this->assertInstanceOf(MorphToMany::class, $this->testModel->tags());
    }

    /** @test */
    public function it_can_attach_a_tag()
    {
        $this->testModel->attachTag('test1');

        $this->assertCount(1, $this->testModel->tags);
    }

    /** @test */
    public function it_can_attach_multiple_tags()
    {
        $this->testModel->attachTag(['test1', 'test2']);

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
    public function it_provides_a_scope_to_get_tags_of_a_certain_type()
    {
        $this->testModel->tags()->attach(Tag::findOrCreate('test', 'type1'));
        $this->testModel->tags()->attach(Tag::findOrCreate('test2', 'type2'));

        $tagsOfType2 = $this->testModel->tagsOfType('type2');

        $this->assertCount(1, $tagsOfType2);
        $this->assertEquals('type2', $tagsOfType2->first()->type);

    }



}
