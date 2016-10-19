<?php

namespace Spatie\Translatable\Test;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Tags\Tag;
use Spatie\Tags\Test\TestCase;
use Spatie\Tags\Test\TestModel;

class HasTagsScopesText extends TestCase
{
    /** @var \Spatie\Tags\Test\TestModel  */
    protected $testModel;

    public function setUp()
    {
        parent::setUp();

        TestModel::create([
            'name' => 'model1',
            'tags' => 'tagA'
        ]);

        TestModel::create([
            'name' => 'model2',
            'tags' => ['tagA', 'tagB'],
        ]);

        TestModel::create([
            'name' => 'model3',
            'tags' => ['tagA', 'tagB', 'tagC'],
        ]);
    }

    /** @test */
    public function it_provides_as_scope_to_get_all_models_that_have_any_of_the_given_tags()
    {
        $testModels = TestModel::withAnyTags(['tagB', 'tagC'])->get();

        $this->assertEquals(['model2', 'model3'], $testModels->pluck('name')->toArray());
    }

    /** @test */
    public function the_with_any_tags_scopes_will_still_items_when_passing_a_non_existing_tag()
    {
        $testModels = TestModel::withAnyTags(['tagB', 'tagC', 'tagD'])->get();
        
        $this->assertEquals(['model2', 'model3'], $testModels->pluck('name')->toArray());
    }
}
