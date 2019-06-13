---
title: Using types
weight: 2
---

In your application you might want to have multiple collections of tags. For example: you might want one group of tags for your `News` model and another group of tags for your `BlogPost` model. 

To create separate collections of tags you can use tag types.

```php
//creating a tag with a certain type
$tagWithType = Tag::findOrCreate('headline', 'newsTag');
```

In addition to strings, all methods mentioned in the basic usage section can take instances of `Tag` as well.

```php
$newsItem->attachTag($tagWithType);
$newsItem->detachTag($tagWithType);
...
```

In addition to `syncTags`, an additional method called `syncTagsWithType` is available for syncing tags on a per-type basis: 

```php
$newsItem->syncTagsWithType(['tagA', 'tagB'], 'firstType');
$newsItem->syncTagsWithType(['tagC', 'tagD'], 'secondType');
```


The provided method scopes, `withAnyTags` and `withAllTags`, can take instances of `Spatie\Tags\Tag` too:

```php
$tag = Tag::create('gossip', 'newsTag');
$tag2 = Tag::create('headline', 'newsTag');

NewsItem::withAnyTags([$tag, $tag2])->get();
```

To get all tags with a specific type use the `getWithType` method.

```php
$tagA = Tag::findOrCreate('tagA', 'firstType');
$tagB = Tag::findOrCreate('tagB', 'firstType');
$tagC = Tag::findOrCreate('tagC', 'secondType');
$tagD = Tag::findOrCreate('tagD', 'secondType');

Tag::getWithType('firstType'); // returns a collection with $tagA and $tagB

//there's also a scoped version
Tag::withType('firstType')->get(); // returns the same result
```

From your model object, you can also get all tags with a specific type via the `tagsWithType` method:

```php
$newsItem->tagsWithType('firstType'); // returns a collection
```
