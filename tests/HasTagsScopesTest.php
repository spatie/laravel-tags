<?php

namespace Spatie\Translatable\Test;

use Spatie\Tags\Tag;
use Spatie\Tags\Test\TestCase;
use Spatie\Tags\Test\TestModel;

class HasTagsScopesTest extends TestCase
{
    /** @var \Spatie\Tags\Test\TestModel */
    protected $testModel;

    public function setUp(): void
    {
        parent::setUp();

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
    }

    /** @test */
    public function it_provides_as_scope_to_get_all_models_that_have_any_of_the_given_tags()
    {
        $testModels = TestModel::withAnyTags(['tagC', 'tagD'])->get();

        $this->assertEquals(['model3', 'model4'], $testModels->pluck('name')->toArray());
    }

    /** @test */
    public function the_with_any_tags_scopes_will_still_items_when_passing_a_non_existing_tag()
    {
        $testModels = TestModel::withAnyTags(['tagB', 'tagC', 'nonExistingTag'])->get();

        $this->assertEquals(['model2', 'model3'], $testModels->pluck('name')->toArray());
    }

    /** @test */
    public function it_provides_as_scope_to_get_all_models_that_have_all_of_the_given_tags()
    {
        $testModels = TestModel::withAllTags(['tagA', 'tagB'])->get();

        $this->assertEquals(['model2', 'model3'], $testModels->pluck('name')->toArray());

        $testModels = TestModel::withAllTags(['tagB', 'tagC'])->get();

        $this->assertEquals(['model3'], $testModels->pluck('name')->toArray());
    }

    /** @test */
    public function it_provides_as_scope_to_get_all_models_that_have_any_of_the_given_tags_with_type()
    {
        $testModels = TestModel::withAnyTags(['tagE'], 'typedTag')->get();

        $this->assertEquals(['model5', 'model6'], $testModels->pluck('name')->toArray());

        $testModels = TestModel::withAnyTags(['tagF'], 'typedTag')->get();

        $this->assertEquals(['model5'], $testModels->pluck('name')->toArray());

        $testModels = TestModel::withAnyTags(['tagF'])->get();

        $this->assertEquals([], $testModels->pluck('name')->toArray());
    }

    /** @test */
    public function it_provides_as_scope_to_get_all_models_that_have_all_of_the_given_tags_with_type()
    {
        $testModels = TestModel::withAllTags(['tagE', 'tagF'], 'typedTag')->get();

        $this->assertEquals(['model5'], $testModels->pluck('name')->toArray());
    }

    /** @test */
    public function it_provides_as_scope_to_get_all_models_that_have_any_of_the_given_tags_with_any_type()
    {
        $testModels = TestModel::withAnyTagsOfAnyType(['tagE', 'tagF'])->get();

        $this->assertEquals(['model5', 'model6'], $testModels->pluck('name')->toArray());
    }

    /** @test */
    public function it_provides_as_scope_to_get_all_models_that_have_all_of_the_given_tags_with_any_type()
    {
        $testModels = TestModel::withAllTagsOfAnyType(['tagE', 'tagF'])->get();

        $this->assertEquals(['model5'], $testModels->pluck('name')->toArray());
    }
}
