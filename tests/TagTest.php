<?php

use Spatie\Tags\Tag;

beforeEach(function () {
    $this->assertCount(0, Tag::all());
});

it('can create a tag', function()
{
    $tag = Tag::findOrCreateFromString('string');

    $this->assertCount(1, Tag::all());
    $this->assertSame('string', $tag->getTranslation('name', app()->getLocale()));
    $this->assertNull($tag->type);
});

it('creates sortable tags',function()
{
    $tag = Tag::findOrCreateFromString('string');
    $this->assertSame(1, $tag->order_column);

    $tag = Tag::findOrCreateFromString('string2');
    $this->assertSame(2, $tag->order_column);
});

it('automatically generates a slug', function()
{
    $tag = Tag::findOrCreateFromString('this is a tag');

    $this->assertSame('this-is-a-tag', $tag->slug);
});


it('uses str slug if config slugger value is empty',function()
{
    config()->set('tags.slugger', null);

    $tag = Tag::findOrCreateFromString('this is a tag');

    $this->assertSame('this-is-a-tag', $tag->slug);
});


it('can use a custom slugger',function()
{
    config()->set('tags.slugger', 'strtoupper');

    $tag = Tag::findOrCreateFromString('this is a tag');

    $this->assertSame('THIS IS A TAG', $tag->slug);
});


it('can create a tag with a type',function()
{
    $tag = Tag::findOrCreate('string', 'myType');

    $this->assertSame('myType', $tag->type);
});


it('provides a scope to get all tags with a specific type',function()
{
    Tag::findOrCreate('tagA', 'firstType');
    Tag::findOrCreate('tagB', 'firstType');
    Tag::findOrCreate('tagC', 'secondType');
    Tag::findOrCreate('tagD', 'secondType');

    $this->assertEquals(['tagA', 'tagB'], Tag::withType('firstType')->pluck('name')->toArray());
    $this->assertEquals(['tagC', 'tagD'], Tag::withType('secondType')->pluck('name')->toArray());
});


it('provides a scope to get all tags the contain a certain string',function()
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
});


it('provides a method to get all tags with a specific type',function()
{
    Tag::findOrCreate('tagA', 'firstType');
    Tag::findOrCreate('tagB', 'firstType');
    Tag::findOrCreate('tagC', 'secondType');
    Tag::findOrCreate('tagD', 'secondType');

    $this->assertEquals(['tagA', 'tagB'], Tag::getWithType('firstType')->pluck('name')->toArray());
    $this->assertEquals(['tagC', 'tagD'], Tag::getWithType('secondType')->pluck('name')->toArray());
});


it('will not create a tag if the tag already exists',function()
{
    Tag::findOrCreate('string');

    Tag::findOrCreate('string');

    $this->assertCount(1, Tag::all());
});


it('will create a tag if a tag exists with the same name but a different type',function()
{
    Tag::findOrCreate('string');

    Tag::findOrCreate('string', 'myType');

    $this->assertCount(2, Tag::all());
});


it('can create tags using an array',function()
{
    Tag::findOrCreate(['tag1', 'tag2', 'tag3']);

    $this->assertCount(3, Tag::all());
});


it('can create tags using a collection',function()
{
    Tag::findOrCreate(collect(['tag1', 'tag2', 'tag3']));

    $this->assertCount(3, Tag::all());
});


it('can store translations',function()
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
});


it('can find or create a tag',function()
{
    $tag = Tag::findOrCreate('string');

    $tag2 = Tag::findOrCreate($tag->name);

    $this->assertEquals('string', $tag2->name);
});


it('can find tags from a string with any type',function()
{
    Tag::findOrCreate('tag1');

    Tag::findOrCreate('tag1', 'myType1');

    Tag::findOrCreate('tag1', 'myType2');

    $tags = Tag::findFromStringOfAnyType('tag1');

    $this->assertCount(3, $tags);
});


it('name can be changed by setting its name property to a new value',function()
{
    $tag = Tag::findOrCreate('my tag');

    $tag->name = 'new name';

    $tag->save();

    $this->assertEquals('new name', $tag->name);
});


it('gets all tag types',function()
{
    Tag::findOrCreate('foo', 'type1');
    Tag::findOrCreate('bar', 'type1');
    Tag::findOrCreate('baz', 'type2');
    Tag::findOrCreate('qux', 'type2');

    $types = Tag::getTypes();

    $this->assertCount(2, $types);
    $this->assertEquals('type1', $types[0]);
    $this->assertEquals('type2', $types[1]);
});
