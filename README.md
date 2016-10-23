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

//retrieve models that have any of the given tags
NewsItem::withAnyTags(['tag1', 'tag2']);

//retrieve models that have all of the given tags
NewsItem::withAllTags(['tag1', 'tag2']);
```

This is the core functionality of almost every other tag package out there. What makes this spatie/laravel-tags unique is the built in support for translations, tag types, slugs, and sortable tags.

```php
//attaching a tag with a type
NewsItem::attachTag(Tag::findOrCreate('string', 'myType'));

// the tag model has a scope to retrieve all tags with a certain type
Tag::type('myType')->get()

// tags can hold translations
$tag = Tag::findOrCreate('my tag'); //uses the app's locale
$tag->setTranslation('fr', 'mon tag');
$tag->setTranslation('nl', 'mijn tag');
$tag->save();

// tags are sortable
$tag = Tag::findOrCreate('my tag');
$tag->order_column //returns 1
$tag2 = Tag::findOrCreate('another tag');
$tag2->order_column //returns 2

// tags have slugs 

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

Optionally you can publish the migration with:

```bash
php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="config"
```

This is the contents of the published `laravel-tags.php` config file:

```php
return [

    /**
     * The model used to manage the tags. You can put any model you want here
     * as long as it extends \Spatie\Tags\Tag
     */
    'model' => \Spatie\Tags\Tag::class,
];
```


## Usage

### Basic usage

To make an Eloquent model taggable just add the `\Spatie\Tags\HasTags` trait to it:

```php
class YourModel extends \Illuminate\Database\Eloquent\Model
{
    use \Spatie\Tags\HasTags;
    
    ...
}
```

#### Attaching tags

Here's how you can add some tags:

```php
//using a string
$yourModel->attachTag('tag 1');

//using an array
$yourModel->attachTag(['tag 2', 'tag 3']);

//using an instance of \Spatie\Tags\Tag
$yourModel->attach(\Spatie\Tags\Tag::createOrFind('tag4'));
```

The tags will be stored in the `tags`-table. WHen using these functions we'll make sure that tags are unique and a model will have a tag attached only once.

#### Detaching tags

Here's how tags can be detached:

```php
//using a string
$yourModel->detachTag('tag 1');

//using an array
$yourModel->detachTags(['tag 2', 'tag 3']);

//using an instance of \Spatie\Tags\Tag
$yourModel->attach(\Spatie\Tags\Tag::Find('tag4'));
```

#### Syncing tags

By syncing tags the package will make sure only the tags given will be attached to the models. All other tags will be detached

```php
$yourModel->syncTags(['tag 2', 'tag 3']);
```

#### Retrieving models with certain tags

The package provides two scopes `withAnyTags` and `withAllTags` that can help you find models with certain tags.

```php
// returns models that have one or more of the given tags
YourModel::withAnyTags(['tag 1', 'tag 2'])->get();

// returns models that have all given tags
YourModel::withAllTags(['tag 1', 'tag 2'])->get();
```

### Using tag types

A tag can have a certain type.

```php
//creating a tag with a certain type
$tag = Tag::create('tag 1, 'my type'):

//a tag is just a regular eloquent model. You can change the type by chaning the `type` property
$tag->type = 'another type';
$tag->save();
```

### Using translations

The tag model is translatable. Behind the scenes [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable) is used. You can use any method provided by that package.

```php
$tag = Tag::findOrCreate('my tag');

$tag->setTranslation('name', 'fr', 'mon tag');
$tag->setTranslation('name', 'nl', 'mijn tag');

$tag->save();

$tag->getTranslation('fr') // returns 'mon tag'
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
