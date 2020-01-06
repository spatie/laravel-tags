# Add tags and taggable behaviour to a Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-tags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-tags)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/laravel-tags/run-tests?label=tests)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-tags.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-tags)
[![StyleCI](https://styleci.io/repos/71335427/shield?branch=master)](https://styleci.io/repos/71335427)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-tags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-tags)

This package offers taggable behaviour for your models. After the package is installed the only thing you have to do is add the `HasTags` trait to an Eloquent model to make it taggable. 

But we didn't stop with the regular tagging capabilities you find in every package. Laravel Tags comes with batteries included. Out of the box it has support for [translating tags](https://docs.spatie.be/laravel-tags/v2/advanced-usage/adding-translations), [multiple tag types](https://docs.spatie.be/laravel-tags/v2/advanced-usage/using-types) and [sorting capabilities](https://docs.spatie.be/laravel-tags/v2/advanced-usage/sorting-tags).

You'll find the documentation on https://docs.spatie.be/laravel-tags/v2/introduction/.

Here are some code examples:

```php
//create a model with some tags, don't forget to put "tags" on $fillable array on referred model
$newsItem = NewsItem::create([
   'name' => 'The Article Title',
   'tags' => ['first tag', 'second tag'], //tags will be created if they don't exist
]);

//attaching tags
$newsItem->attachTag('third tag');
$newsItem->attachTags(['fourth tag', 'fifth tag']);

//detaching tags
$newsItem->detachTags('third tag');
$newsItem->detachTags(['fourth tag', 'fifth tag']);

//syncing tags
$newsItem->syncTags(['first tag', 'second tag']); // all other tags on this model will be detached

//syncing tags with a type
$newsItem->syncTagsWithType(['category 1', 'category 2'], 'categories'); 
$newsItem->syncTagsWithType(['topic 1', 'topic 2'], 'topics'); 

//retrieving tags with a type
$newsItem->tagsWithType('categories'); 
$newsItem->tagsWithType('topics'); 

//retrieving models that have any of the given tags
NewsItem::withAnyTags(['first tag', 'second tag'])->get();

//retrieve models that have all of the given tags
NewsItem::withAllTags(['first tag', 'second tag'])->get();

//translating a tag
$tag = Tag::findOrCreate('my tag');
$tag->setTranslation('name', 'fr', 'mon tag');
$tag->setTranslation('name', 'nl', 'mijn tag');
$tag->save();

//getting translations
$tag->translate('name'); //returns my name
$tag->translate('name', 'fr'); //returns mon tag (optional locale param)

//convenient translations through taggable models
$newsItem->tagsTranslated();// returns tags with slug_translated and name_translated properties
$newsItem->tagsTranslated('fr');// returns tags with slug_translated and name_translated properties set for specified locale

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

This package requires Laravel 5.8 or higher, PHP 7.2 or higher and a database that supports `json` fields and MySQL compatible functions.

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
     * Defaults to Str::slug (https://laravel.com/docs/5.8/helpers#method-str-slug)
     */
    'slugger' => null, 
];
```


## Documentation
You'll find the documentation on [https://docs.spatie.be/laravel-tags/v2](https://docs.spatie.be/laravel-tags/v2).

Find yourself stuck using the package? Found a bug? Do you have general questions or suggestions for improving the `laravel-tags` package? Feel free to [create an issue on GitHub](https://github.com/spatie/laravel-tags/issues), we'll try to address it as soon as possible.

If you've found a bug regarding security please mail [freek@spatie.be](mailto:freek@spatie.be) instead of using the issue tracker.

## Testing

1. Copy `.env.example` to `.env` and fill in your database credentials.
2. Run `composer test`.

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

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/open-source).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
