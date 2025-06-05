<div align="left">
    <a href="https://spatie.be/open-source?utm_source=github&utm_medium=banner&utm_campaign=laravel-tags">
      <picture>
        <source media="(prefers-color-scheme: dark)" srcset="https://spatie.be/packages/header/laravel-tags/html/dark.webp">
        <img alt="Logo for laravel-tags" src="https://spatie.be/packages/header/laravel-tags/html/light.webp">
      </picture>
    </a>

<h1>Add tags and taggable behaviour to a Laravel app</h1>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-tags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-tags)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-tags/run-tests.yml?label=tests)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-tags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-tags)
    
</div>

This package offers taggable behaviour for your models. After the package is installed the only thing you have to do is add the `HasTags` trait to an Eloquent model to make it taggable. 

But we didn't stop with the regular tagging capabilities you find in every package. Laravel Tags comes with batteries included. Out of the box it has support for [translating tags](https://docs.spatie.be/laravel-tags/v4/advanced-usage/adding-translations), [multiple tag types](https://docs.spatie.be/laravel-tags/v4/advanced-usage/using-types) and [sorting capabilities](https://docs.spatie.be/laravel-tags/v4/advanced-usage/sorting-tags).

You'll find the documentation on https://spatie.be/docs/laravel-tags.

Here are some code examples:

```php
// apply HasTags trait to a model
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class NewsItem extends Model
{
    use HasTags;
    
    // ...
}
```

```php

// create a model with some tags
$newsItem = NewsItem::create([
   'name' => 'The Article Title',
   'tags' => ['first tag', 'second tag'], //tags will be created if they don't exist
]);

// attaching tags
$newsItem->attachTag('third tag');
$newsItem->attachTag('third tag','some_type');
$newsItem->attachTags(['fourth tag', 'fifth tag']);
$newsItem->attachTags(['fourth_tag','fifth_tag'],'some_type');

// detaching tags
$newsItem->detachTag('third tag');
$newsItem->detachTag('third tag','some_type');
$newsItem->detachTags(['fourth tag', 'fifth tag']);
$newsItem->detachTags(['fourth tag', 'fifth tag'],'some_type');

// get all tags of a model
$newsItem->tags;

// syncing tags
$newsItem->syncTags(['first tag', 'second tag']); // all other tags on this model will be detached

// syncing tags with a type
$newsItem->syncTagsWithType(['category 1', 'category 2'], 'categories'); 
$newsItem->syncTagsWithType(['topic 1', 'topic 2'], 'topics'); 

// retrieving tags with a type
$newsItem->tagsWithType('categories'); 
$newsItem->tagsWithType('topics'); 

// retrieving models that have any of the given tags
NewsItem::withAnyTags(['first tag', 'second tag'])->get();

// retrieve models that have all of the given tags
NewsItem::withAllTags(['first tag', 'second tag'])->get();

// retrieve models that don't have any of the given tags
NewsItem::withoutTags(['first tag', 'second tag'])->get();

// translating a tag
$tag = Tag::findOrCreate('my tag');
$tag->setTranslation('name', 'fr', 'mon tag');
$tag->setTranslation('name', 'nl', 'mijn tag');
$tag->save();

// getting translations
$tag->translate('name'); //returns my name
$tag->translate('name', 'fr'); //returns mon tag (optional locale param)

// convenient translations through taggable models
$newsItem->tagsTranslated();// returns tags with slug_translated and name_translated properties
$newsItem->tagsTranslated('fr');// returns tags with slug_translated and name_translated properties set for specified locale

// using tag types
$tag = Tag::findOrCreate('tag 1', 'my type');

// tags have slugs
$tag = Tag::findOrCreate('yet another tag');
$tag->slug; //returns "yet-another-tag"

// tags are sortable
$tag = Tag::findOrCreate('my tag');
$tag->order_column; //returns 1
$tag2 = Tag::findOrCreate('another tag');
$tag2->order_column; //returns 2

// manipulating the order of tags
$tag->swapOrder($anotherTag);

// checking if a model has a tag
$newsItem->hasTag('first tag');
$newsItem->hasTag('first tag', 'some_type');

// Retrieve models that have tags of a 
Model::withAnyTagsOfType('type');
Model::withAnyTagsOfType(['first type', 'second type']);
```

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-tags.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-tags)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Requirements

This package requires Laravel 8 or higher, PHP 8 or higher, and a database that supports `json` fields and MySQL compatible functions.

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-tags
```

The package will automatically register itself.

You can publish the migration with:
```bash
php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="tags-migrations"
```

After the migration has been published you can create the `tags` and `taggables` tables by running the migrations:

```bash
php artisan migrate
```

You can optionally publish the config file with:
```bash
php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="tags-config"
```

This is the contents of the published config file:

```php
return [

    /*
     * The given function generates a URL friendly "slug" from the tag name property before saving it.
     * Defaults to Str::slug (https://laravel.com/docs/5.8/helpers#method-str-slug)
     */
    'slugger' => null, 
];
```

## Documentation
You'll find the documentation on [https://docs.spatie.be/laravel-tags/v4](https://spatie.be/docs/laravel-tags).

Find yourself stuck using the package? Found a bug? Do you have general questions or suggestions for improving the `laravel-tags` package? Feel free to [create an issue on GitHub](https://github.com/spatie/laravel-tags/issues), we'll try to address it as soon as possible.

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Testing

1. Copy `phpunit.xml.dist` to `phpunit.xml` and fill in your database credentials.
2. Run `composer test`.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Kruikstraat 22, 2018 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
