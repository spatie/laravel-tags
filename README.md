Work in progress, do not use yet...

# Add tags and taggable behaviour to a Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-tags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-tags/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-tags)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/b9e28680-fffe-4e6f-90fa-8c83417f6a86.svg?style=flat-square)](https://insight.sensiolabs.com/projects/b9e28680-fffe-4e6f-90fa-8c83417f6a86)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-tags.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-tags)
[![StyleCI](https://styleci.io/repos/71335427/shield?branch=master)](https://styleci.io/repos/71335427)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-tags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-tags)

This package provides you with a `HasTags` trait with which you can easily add tags to your Eloquent models. Once the package is set up you can do stuff like this:

```php
// create a model with some tags
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
$newsItem->syncTags(['tag1', 'tag2');

//retrieve models that of any of the given tags
NewsItem::withAnyTags(['tag1, 'tag2']);

//retrieve models that have all of the given tags
NewsItem::withAllTags(['tag1, 'tag2']);
```

This is the core functionality of almost every other tag package out there. What makes this spatie/laravel-tags unique is the built in support for translation, tag types, slugs, and sortable tags.

```php
//attaching a tag with a type
NewsItem::attachTag(Tag::findOrCreate('string', 'myType'));

// the tag model has a scope to retrieve all models with a certain tag
Tag::type('myType')->get()

// tags can hold translation
$tag = Tag::findOrCreate('my tag'); //uses the app's locale
$tag->setTranslation('fr', 'mon tag');
$tag->setTranslation('nl', 'mijn tag');
$tag->save();

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

## Usage

``` php
$skeleton = new Spatie\Tags();
echo $skeleton->echoPhrase('Hello, Spatie!');
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

This packages uses `json` fields so we can't use sqlite to run our tests. To run the test you should first setup a MySQL 5.7 database called `laravel-tags`, username should be `root`, `password` should be blank.

``` bash
$ composer test
```

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
