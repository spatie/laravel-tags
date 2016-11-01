# Add tags and taggable behaviour to a Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-tags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-tags/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-tags)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/b9e28680-fffe-4e6f-90fa-8c83417f6a86.svg?style=flat-square)](https://insight.sensiolabs.com/projects/b9e28680-fffe-4e6f-90fa-8c83417f6a86)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-tags.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-tags)
[![StyleCI](https://styleci.io/repos/71335427/shield?branch=master)](https://styleci.io/repos/71335427)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-tags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-tags)

This package offers taggable behaviour for your models. After the package is installed the only thing you have to do is add the `HasTags` trait to an Eloquent model to make it taggable. 

But we didn't stop with the regular tagging capabilities you find in every package. Laravel Tags comes with batteries included. Out of the box it has support for [translating tags](/laravel-tags/v1/advanced-usage/adding-translations), [multiple tag types](/laravel-tags/v1/advanced-usage/using-types) and [sorting capabilities](/laravel-tags/v1/advanced-usage/sorting-tags).

Here are some code examples:

```php
//create a model with some tags
$newsItem = NewsItem::create([
   'name' => 'testModel',
   'tags' => ['tag', 'tag2'], //tags will be created if they don't exist
]);

//attaching tags
$newsItem->attachTag('tag3');
$newsItem->attachTags(['tag4', 'tag5']);

//detaching tags
$newsItem->detachTags('tag3');
$newsItem->detachTags(['tag4', 'tag5']);

//syncing tags
$newsItem->syncTags(['tag1', 'tag2']); // all other tags on this model will be detached

//retrieving models that have any of the given tags
NewsItem::withAnyTags(['tag1', 'tag2']);

//retrieve models that have all of the given tags
NewsItem::withAllTags(['tag1', 'tag2']);

//translating a tag
$tag = Tag::findOrCreate('my tag');
$tag->setTranslation('fr', 'mon tag');
$tag->setTranslation('nl', 'mijn tag');
$tag->save();

//using tag types
$tag = Tag::create('tag 1', 'my type');

//tags have slugs
$tag = Tag::create('yet another tag');
$tag->slug; //returns "yet-another-tag"

//tags are sortable
$tag = Tag::findOrCreate('my tag');
$tag->order_column; //returns 1
$tag2 = Tag::findOrCreate('another tag');
$tag2->order_column; //returns 2

//manipulating the order of tags
$tag->swapOrder($anotherTag);
```

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment you are required to send us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

The best postcards will get published on the open source page on our website.

## Requirements

This package requires Laravel 5.3 or higher, PHP 7.0 or higher and a database that supports `json` fields such as MySQL 5.7 or higher.

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-tags
```

Next up, the service provider must be registered:

```php
// config/app.php
'providers' => [
    ...
    Spatie\Tags\TagsServiceProvider::class,

];
```

You can publish the migration with:
```bash
php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="migrations"
```

After the migration has been published you can create the `tags` and `taggables` tables by running the migrations:

```bash
php artisan migrate
```

## Documentation
You'll find the documentation on [https://docs.spatie.be/laravel-medialibrary/v1](https://docs.spatie.be/laravel-tags/v1).

Find yourself stuck using the package? Found a bug? Do you have general questions or suggestions for improving the media library? Feel free to [create an issue on GitHub](https://github.com/spatie/laravel-tags/issues), we'll try to address it as soon as possible.

If you've found a bug regarding security please mail [freek@spatie.be](mailto:freek@spatie.be) instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
