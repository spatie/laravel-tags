---
title: Using tags
weight: 1
---

To make an Eloquent model taggable just add the `\Spatie\Tags\HasTags` trait to it:

```php
class YourModel extends \Illuminate\Database\Eloquent\Model
{
    use \Spatie\Tags\HasTags;
    
    ...
}
```

## Attaching tags

Here's how you can add some tags:

```php
//adding a single tag
$yourModel->attachTag('tag 1');

//adding multiple tags
$yourModel->attachTags(['tag 2', 'tag 3']);

//using an instance of \Spatie\Tags\Tag
$yourModel->attach(\Spatie\Tags\Tag::findOrCreate('tag4'));
```

The tags will be stored in the `tags`-table. When using these functions we'll make sure that tags are unique and a model will have a tag attached only once.

## Detaching tags

Here's how tags can be detached:

```php
//using a string
$yourModel->detachTag('tag 1');

//using an array
$yourModel->detachTags(['tag 2', 'tag 3']);

//using an instance of \Spatie\Tags\Tag
$yourModel->detach(\Spatie\Tags\Tag::Find('tag4'));
```

## Syncing tags

By syncing tags the package will make sure only the tags given will be attached to the models. All other tags that were on the model will be detached.

```php
$yourModel->syncTags(['tag 2', 'tag 3']);
```

## Managing tags

Tags are stored in the `tags` table and can be managed with the included `Spatie\Tags\Tag`-model.

```php
//create a tag
$tag = Tag::create(['name' => 'my tag']);

//update a tag
$tag->name = 'another tag';
$tag->save();

//use "findFromString" instead of "find" to retrieve a certain tag
$tag = Tag::findFromString('another tag')

//create a tag if it doesn't exist yet
$tag = Tag::findOrCreateFromString('yet another tag');

//delete a tag
$tag->delete();
```

## Finding tags

You can find all tags containing a specific value with the `containing` scope.

```php
Tag::create('one');
Tag::create('another-one');
Tag::create('another-ONE-with-different-casing');
Tag::create('two');

Tag::containing('on')->get(); // will return all tags except `two`
```
