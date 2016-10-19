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
    public function it_provides_a_scope_to_get_tags_of_a_certain_type()
    {
        $this->testModel->tags()->attach(Tag::fromString('test', 'type1'));
        $this->testModel->tags()->attach(Tag::fromString('test2', 'type2'));

        $tagsOfType2 = $this->testModel->tagsOfType('type2');

        $this->assertCount(1, $tagsOfType2);
        $this->assertEquals('type2', $tagsOfType2->first()->type);

    }



}
