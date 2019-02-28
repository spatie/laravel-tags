<?php

namespace Spatie\Translatable\Test;

use Spatie\Tags\Tag;
use Spatie\Tags\Test\TestCase;

class TagTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->assertCount(0, Tag::all());
    }

    /** @test */
    public function it_can_create_a_tag()
    {
        $tag = Tag::findOrCreateFromString('string');

        $this->assertCount(1, Tag::all());
        $this->assertSame('string', $tag->getTranslation('name', app()->getLocale()));
        $this->assertNull($tag->type);
    }

    /** @test */
    public function it_creates_sortable_tags()
    {
        $tag = Tag::findOrCreateFromString('string');
        $this->assertSame(1, $tag->order_column);

        $tag = Tag::findOrCreateFromString('string2');
        $this->assertSame(2, $tag->order_column);
    }

    /** @test */
    public function it_automatically_generates_a_slug()
    {
        $tag = Tag::findOrCreateFromString('this is a tag');

        $this->assertSame('this-is-a-tag', $tag->slug);
    }

    /** @test */
    public function it_uses_str_slug_if_config_slugger_value_is_empty()
    {
        app('config')->set('tags.slugger', null);

        $tag = Tag::findOrCreateFromString('this is a tag');

        $this->assertSame('this-is-a-tag', $tag->slug);
    }

    /** @test */
    public function it_can_use_a_custom_slugger()
    {
        app('config')->set('tags.slugger', 'strtoupper');

        $tag = Tag::findOrCreateFromString('this is a tag');

        $this->assertSame('THIS IS A TAG', $tag->slug);
    }

    /** @test */
    public function it_can_create_a_tag_with_a_type()
    {
        $tag = Tag::findOrCreate('string', 'myType');

        $this->assertSame('myType', $tag->type);
    }

    /** @test */
    public function it_provides_a_scope_to_get_all_tags_with_a_specific_type()
    {
        Tag::findOrCreate('tagA', 'firstType');
        Tag::findOrCreate('tagB', 'firstType');
        Tag::findOrCreate('tagC', 'secondType');
        Tag::findOrCreate('tagD', 'secondType');

        $this->assertEquals(['tagA', 'tagB'], Tag::withType('firstType')->pluck('name')->toArray());
        $this->assertEquals(['tagC', 'tagD'], Tag::withType('secondType')->pluck('name')->toArray());
    }

    /** @test */
    public function it_provides_a_scope_to_get_all_tags_the_contain_a_certain_string()
    {
        Tag::findOrCreate('one');
        Tag::findOrCreate('another-one');
        Tag::findOrCreate('another-ONE-with-different-casing');
        Tag::findOrCreate('two');

        $this->assertEquals([
            'one',
            'another-one',
            'another-ONE-with-different-casing',
        ], Tag::containing('on')->pluck('name')->toArray());
        $this->assertEquals(['two'], Tag::containing('tw')->pluck('name')->toArray());
    }

    /** @test */
    public function it_provides_a_method_to_get_all_tags_with_a_specific_type()
    {
        Tag::findOrCreate('tagA', 'firstType');
        Tag::findOrCreate('tagB', 'firstType');
        Tag::findOrCreate('tagC', 'secondType');
        Tag::findOrCreate('tagD', 'secondType');

        $this->assertEquals(['tagA', 'tagB'], Tag::getWithType('firstType')->pluck('name')->toArray());
        $this->assertEquals(['tagC', 'tagD'], Tag::getWithType('secondType')->pluck('name')->toArray());
    }

    /** @test */
    public function it_will_not_create_a_tag_if_the_tag_already_exists()
    {
        Tag::findOrCreate('string');

        Tag::findOrCreate('string');

        $this->assertCount(1, Tag::all());
    }

    /** @test */
    public function it_will_create_a_tag_if_a_tag_exists_with_the_same_name_but_a_different_type()
    {
        Tag::findOrCreate('string');

        Tag::findOrCreate('string', 'myType');

        $this->assertCount(2, Tag::all());
    }

    /** @test */
    public function it_can_create_tags_using_an_array()
    {
        Tag::findOrCreate(['tag1', 'tag2', 'tag3']);

        $this->assertCount(3, Tag::all());
    }

    /** @test */
    public function it_can_create_tags_using_a_collection()
    {
        Tag::findOrCreate(collect(['tag1', 'tag2', 'tag3']));

        $this->assertCount(3, Tag::all());
    }

    /** @test */
    public function it_can_store_translations()
    {
        $tag = Tag::findOrCreate('my tag');

        $tag->setTranslation('name', 'fr', 'mon tag');
        $tag->setTranslation('name', 'nl', 'mijn tag');

        $tag->save();

        $this->assertEquals([
            'en' => 'my tag',
            'fr' => 'mon tag',
            'nl' => 'mijn tag',
        ], $tag->getTranslations('name'));
    }

    public function it_can_find_or_create_a_tag()
    {
        $tag = Tag::findOrCreate('string');

        $tag2 = Tag::findOrCreate($tag);

        $this->assertEquals('string', $tag2->name);
    }

    /** @test */
    public function its_name_can_be_changed_by_setting_its_name_property_to_a_new_value()
    {
        $tag = Tag::findOrCreate('my tag');

        $tag->name = 'new name';

        $tag->save();

        $this->assertEquals('new name', $tag->name);
    }
}
