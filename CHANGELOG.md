# Changelog

All notable changes to `laravel-tags` will be documented in this file

## 4.0.0 - 2021-03-09

- drop support for all PHP versions below 8.0

## 3.1.0 - 2021-03-01

-add `tag_model` config variable (#301)

## 3.0.2 - 2020-12-30

- Get all registered tag types (#296)

## 3.0.1 - 2020-11-05

- add support for PHP 8

## 3.0.0 - 2020-09-08

- drop support for anything below Laravel 8
- add `HasFactory` to tags model.

## 2.7.2 - 2020-09-08

- add support for Laravel 8

## 2.7.1 - 2020-08-24

- avoid duplicate error on syncTagsWithType (#274)

## 2.7.0 - 2020-08-24

- allow specifying type when using `attachTags()` or `detachTags()` (#273)

## 2.6.2 - 2020-05-28

- change mutator behaviour: use sync instead of attach (#260)

## 2.6.0 - 2020-03-03

- add support for Laravel 7

## 2.5.4 - 2020-02-12

- make sure each tag is unique on the database level (#251)

## 2.5.3 - 2019-11-07

- use `morphs` in migration

## 2.5.2 - 2019-09-29

- `findOrCreateFromString` can now be overloaded (#231)

## 2.5.1 - 2019-09-08

- make `scopeContaining` compatible with Postgresql

## 2.5.0 - 2019-09-04

- add support for Laravel 6

## 2.4.5 - 2019-07-18

- fix `scopeWithAllTags`* scopes

## 2.4.4 - 2019-04-17

- use `ordered()` scope to determine order column name (#193)

## 2.4.3 - 2019-04-10

- fix `str_slug` being deprecated

## 2.4.2 - 2019-04-10

- added ability to work with multibyte charset

## 2.4.1 - 2019-03-06

- performance enhanchements

## 2.4.0 - 2019-03-01

- add `tagsTranslated`

## 2.3.0 - 2019-02-27

- drop support for Laravel 5.7 and below
- drop support for PHP 7.1 and below

## 2.2.2 - 2019-02-27

- add support for Laravel 5.8

## 2.2.1 - 2019-01-19

- use morph map name for taggable_type if it exists

## 2.2.0 - 2018-12-21

- add`scopeWithAllTagsOfAnyType` and `scopeWithAnyTagsOfAnyType`

## 2.1.6 - 2018-12-20

- update deps

## 2.1.5 - 2018-11-27

- fix for locales with hypens in their name

## 2.1.4 - 2018-11-03

- use `getTable()` instead of `$table` in the scope

## 2.1.3 - 2018-01-29

- fix for `withAnyTags`

## 2.1.2 - 2018-10-24

- add support for laravel-translatable v3

## 2.1.1 - 2018-09-17

- improve performance

## 2.1.0 - 2018-08-27

- add `containing` scope

## 2.0.2 - 2018-08-24

- add L5.7 compatibility

## 2.0.1 - 2018-02-08

- add L5.6 compatibility

## 2.0.0 - 2017-08-31

- added compatiblity with Laravel 5.5, dropped support for all older versions
- renamed config file from `laravel-tags` to `tags`

## 1.4.1 - 2017-06-18

- deleting a model with tags will now delete related records in the `taggables` table

## 1.4.0 - 2017-05-25

- add `syncWithType`

## 1.3.5 - 2017-02-13

- allow the name of a tag to be set by changing the name property

## 1.3.4 - 2017-02-07

- fix bug where the same tag would be created multiple times

## 1.3.3 - 2017-02-06

- removed typehint from HasTags::convertToTags to allow and instance of `Tag` to be passed in

## 1.3.2 - 2016-01-23

- remove classmap from `composer.json`

## 1.3.1 - 2016-01-23

**THIS VERSION IS BROKEN, DO NOT USE**

- fix missings deps

## 1.3.0 - 2016-01-23

**THIS VERSION IS BROKEN, DO NOT USE**

- add compatibility with Laravel 5.4

## 1.2.0 - 2016-11-14

- the `withAllTags` and `withAnyTags` scopes now optionally accept a `type`

## 1.1.1 - 2016-11-08

- fix bug where a custom tag model would not be used in `tags()`

## 1.1.0 - 2016-11-03

- The function that determines the value of the slug can now be modified in the config file

## 1.0.2 - 2016-11-01

- fixed bug in `attachTag` where using a `Tag` model would be converted to a string

## 1.0.1 - 2016-10-30

- fix migration path in service provider

## 1.0.0 - 2016-10-28

- initial release
