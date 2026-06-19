<?php

use Illuminate\Support\Facades\DB;
use Spatie\Tags\Tag;

beforeEach(function () {
    expect(Tag::all())->toHaveCount(0);
});

it('can create a tag', function () {
    $tag = Tag::findOrCreateFromString('string');

    expect(Tag::all())->toHaveCount(1);
    expect($tag->getTranslation('name', app()->getLocale()))->toBe('string');
    expect($tag->type)->toBeNull();
});

it('creates sortable tags', function () {
    $tag = Tag::findOrCreateFromString('string');
    expect($tag->order_column)->toBe(1);

    $tag = Tag::findOrCreateFromString('string2');
    expect($tag->order_column)->toBe(2);
});

it('creates sortable tags with a custom order column', function () {
    $tag = Tag::findOrCreateFromString('string');
    $tag->order_column = 10;
    $tag->save();

    $tag2 = Tag::findOrCreateFromString('string 2');
    $tag2->order_column = 20;
    $tag2->save();

    expect($tag->order_column)->toBe(10);
    expect($tag2->order_column)->toBe(20);
});

it('automatically generates a slug', function () {
    $tag = Tag::findOrCreateFromString('this is a tag');

    expect($tag->slug)->toBe('this-is-a-tag');
});


it('uses str slug if config slugger value is empty', function () {
    config()->set('tags.slugger', null);

    $tag = Tag::findOrCreateFromString('this is a tag');

    expect($tag->slug)->toBe('this-is-a-tag');
});


it('can use a custom slugger', function () {
    config()->set('tags.slugger', 'strtoupper');

    $tag = Tag::findOrCreateFromString('this is a tag');

    expect($tag->slug)->toBe('THIS IS A TAG');
});


it('can create a tag with a type', function () {
    $tag = Tag::findOrCreate('string', 'myType');

    expect($tag->type)->toBe('myType');
});


it('provides a scope to get all tags with a specific type', function () {
    Tag::findOrCreate('tagA', 'firstType');
    Tag::findOrCreate('tagB', 'firstType');
    Tag::findOrCreate('tagC', 'secondType');
    Tag::findOrCreate('tagD', 'secondType');

    expect(Tag::withType('firstType')->pluck('name')->toArray())->toMatchArray(['tagA', 'tagB']);
    expect(Tag::withType('secondType')->pluck('name')->toArray())->toMatchArray(['tagC', 'tagD']);
});


it('provides a scope to get all tags the contain a certain string', function () {
    Tag::findOrCreate('one');
    Tag::findOrCreate('another-one');
    Tag::findOrCreate('another-ONE-with-different-casing');
    Tag::findOrCreate('two');

    expect(Tag::containing('on')->pluck('name')->toArray())->toMatchArray([
        'one',
        'another-one',
        'another-ONE-with-different-casing',
    ]);
    expect(Tag::containing('tw')->pluck('name')->toArray())->toMatchArray(['two']);
});


it('provides a method to get all tags with a specific type', function () {
    Tag::findOrCreate('tagA', 'firstType');
    Tag::findOrCreate('tagB', 'firstType');
    Tag::findOrCreate('tagC', 'secondType');
    Tag::findOrCreate('tagD', 'secondType');

    expect(Tag::getWithType('firstType')->pluck('name')->toArray())->toMatchArray(['tagA', 'tagB']);
    expect(Tag::getWithType('secondType')->pluck('name')->toArray())->toMatchArray(['tagC', 'tagD']);
});


it('will not create a tag if the tag already exists', function () {
    Tag::findOrCreate('string');

    Tag::findOrCreate('string');

    expect(Tag::all())->toHaveCount(1);
});


it('will create a tag if a tag exists with the same name but a different type', function () {
    Tag::findOrCreate('string');

    Tag::findOrCreate('string', 'myType');

    expect(Tag::all())->toHaveCount(2);
});


it('can create tags using an array', function () {
    Tag::findOrCreate(['tag1', 'tag2', 'tag3']);

    expect(Tag::all())->toHaveCount(3);
});


it('can create tags using a collection', function () {
    Tag::findOrCreate(collect(['tag1', 'tag2', 'tag3']));

    expect(Tag::all())->toHaveCount(3);
});


it('reuses existing tags when given an array of mixed existing and new values', function () {
    $existing = Tag::findOrCreate(['tag1', 'tag2']);

    $tags = Tag::findOrCreate(['tag1', 'tag2', 'tag3']);

    expect(Tag::all())->toHaveCount(3);
    expect($tags->take(2)->pluck('id')->all())->toBe($existing->pluck('id')->all());
});


it('does not create duplicates for repeated values in a single call', function () {
    $tags = Tag::findOrCreate(['tag1', 'tag1']);

    expect(Tag::all())->toHaveCount(1);
    expect($tags->first()->id)->toBe($tags->last()->id);
});


it('accepts a mix of tag instances and strings', function () {
    $existing = Tag::findOrCreate('tag1');

    $tags = Tag::findOrCreate([$existing, 'tag2']);

    expect(Tag::all())->toHaveCount(2);
    expect($tags->first()->id)->toBe($existing->id);
    expect($tags->last()->name)->toBe('tag2');
});


it('matches an existing tag by its slug when given an array', function () {
    $tag = Tag::findOrCreate('Some Tag');
    expect($tag->slug)->toBe('some-tag');

    $tags = Tag::findOrCreate(['some-tag']);

    expect(Tag::all())->toHaveCount(1);
    expect($tags->first()->id)->toBe($tag->id);
});


it('resolves an array of tags using a single lookup query', function () {
    Tag::findOrCreate(['tag1', 'tag2']);

    DB::enableQueryLog();

    Tag::findOrCreate(['tag1', 'tag2']);

    $selects = collect(DB::getQueryLog())
        ->filter(fn ($query) => str_starts_with(strtolower(trim($query['query'])), 'select'));

    DB::disableQueryLog();

    expect($selects)->toHaveCount(1);
});


it('can store translations', function () {
    $tag = Tag::findOrCreate('my tag');

    $tag->setTranslation('name', 'fr', 'mon tag');
    $tag->setTranslation('name', 'nl', 'mijn tag');

    $tag->save();

    expect($tag->getTranslations('name'))->toMatchArray([
        'en' => 'my tag',
        'fr' => 'mon tag',
        'nl' => 'mijn tag',
    ]);
});


it('can find or create a tag', function () {
    $tag = Tag::findOrCreate('string');

    $tag2 = Tag::findOrCreate($tag->name);

    expect($tag2->name)->toBe('string');
});


it('can find tags from a string with any type', function () {
    Tag::findOrCreate('tag1');

    Tag::findOrCreate('tag1', 'myType1');

    Tag::findOrCreate('tag1', 'myType2');

    $tags = Tag::findFromStringOfAnyType('tag1');

    expect($tags)->toHaveCount(3);
});


it('name can be changed by setting its name property to a new value', function () {
    $tag = Tag::findOrCreate('my tag');

    $tag->name = 'new name';

    $tag->save();

    expect($tag->name)->toBe('new name');
});


it('gets all tag types', function () {
    Tag::findOrCreate('foo', 'type1');
    Tag::findOrCreate('bar', 'type1');
    Tag::findOrCreate('baz', 'type2');
    Tag::findOrCreate('qux', 'type2');

    $types = Tag::getTypes();

    expect($types)->toHaveCount(2);
    expect($types[0])->toBe('type1');
    expect($types[1])->toBe('type2');
});
