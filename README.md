# Add tags and taggable behaviour to a Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-tags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-tags/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-tags)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-tags.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-tags)
[![StyleCI](https://styleci.io/repos/71335427/shield?branch=master)](https://styleci.io/repos/71335427)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-tags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-tags)

This package offers taggable behaviour for your models. After the package is installed the only thing you have to do is add the `HasTags` trait to an Eloquent model to make it taggable. 

But we didn't stop with the regular tagging capabilities you find in every package. Laravel Tags comes with batteries included. Out of the box it has support for [translating tags](https://docs.spatie.be/laravel-tags/v2/advanced-usage/adding-translations), [multiple tag types](https://docs.spatie.be/laravel-tags/v2/advanced-usage/using-types) and [sorting capabilities](https://docs.spatie.be/laravel-tags/v2/advanced-usage/sorting-tags).

You'll find the documentation on https://docs.spatie.be/laravel-tags/v2.

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

//syncing tags with a type
$newsItem->syncTagsWithType(['tag1', 'tag2'], 'typeA'); 
$newsItem->syncTagsWithType(['tag1', 'tag2'], 'typeB'); 

//retrieving tags with a type
$newsItem->tagsWithType('typeA'); 
$newsItem->tagsWithType('typeB'); 

//retrieving models that have any of the given tags
NewsItem::withAnyTags(['tag1', 'tag2'])->get();

//retrieve models that have all of the given tags
NewsItem::withAllTags(['tag1', 'tag2'])->get();

//translating a tag
$tag = Tag::findOrCreate('my tag');
$tag->setTranslation('fr', 'mon tag');
$tag->setTranslation('nl', 'mijn tag');
$tag->save();

//using tag types
$tag = Tag::findOrCreate('tag 1', 'my type');

//tags have slugs
$tag = Tag::findOrCreate('yet another tag');
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

## Requirements

This package requires Laravel 5.5 or higher, PHP 7.0 or higher and a database that supports `json` fields and functions such as MySQL 5.7 or higher.

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-tags
```

The package will automatically register itself.

You can publish the migration with:
```bash
php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="migrations"
```

After the migration has been published you can create the `tags` and `taggables` tables by running the migrations:

```bash
php artisan migrate
```

You can optionally publish the config file with:
```bash
php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [

    /*
     * The given function generates a URL friendly "slug" from the tag name property before saving it.
     */
    'slugger' => 'str_slug',
];
```


## Documentation
You'll find the documentation on [https://docs.spatie.be/laravel-tags/v2](https://docs.spatie.be/laravel-tags/v2).

Find yourself stuck using the package? Found a bug? Do you have general questions or suggestions for improving the `laravel-tags` package? Feel free to [create an issue on GitHub](https://github.com/spatie/laravel-tags/issues), we'll try to address it as soon as possible.

If you've found a bug regarding security please mail [freek@spatie.be](mailto:freek@spatie.be) instead of using the issue tracker.

## Testing

1. Copy `.env.example` to `.env` and fill in your database credentials.
2. Run `vendor/bin/phpunit`.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
