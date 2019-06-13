---
title: Adding translations
weight: 1
---

When using the package like described in the basic usage section all tags will be stored in the locale your app is running. If you're creating a multilingual app it's really easy to translate the tags. Here's a quick example.

```php
$tag = Tag::findOrCreate('my tag'); //store in the current locale of your app

//let's add some translation for other languages
$tag->setTranslation('name', 'fr', 'mon tag');
$tag->setTranslation('name', 'nl', 'mijn tag');

//don't forget to save the model
$tag->save();

$tag->getTranslation('name', 'fr'); // returns 'mon tag'

$tag->name // returns the name of the tag in current locale of your app.
```

The translations of the tags are stored in the `name` column of the `tags` table. It's a `json` column. To find a tag with a specific translation you can just use Laravel's query builder which has support for `json` columns.

```php
 \Spatie\Tags\Tag
   ->where('name->fr', 'mon tag')
   ->first();
```

Behind the scenes [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable) is used. You can use any method provided by that package.


