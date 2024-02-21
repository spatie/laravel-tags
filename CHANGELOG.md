# Changelog

All notable changes to `laravel-tags` will be documented in this file

## 4.6.0 - 2024-02-21

### What's Changed

* Add laravel 11 support by @mokhosh in https://github.com/spatie/laravel-tags/pull/493

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.5.2...4.6.0

## 4.5.2 - 2024-01-19

### What's Changed

* Bump stefanzweifel/git-auto-commit-action from 4 to 5 by @dependabot in https://github.com/spatie/laravel-tags/pull/485
* Bump actions/checkout from 3 to 4 by @dependabot in https://github.com/spatie/laravel-tags/pull/480
* fix: set public visibility for findOrCreateFromString by @KnightYoshi in https://github.com/spatie/laravel-tags/pull/491
* fix: Fixing issue when using a different table to store pivot data by @jordanmiguel in https://github.com/spatie/laravel-tags/pull/492

### New Contributors

* @KnightYoshi made their first contribution in https://github.com/spatie/laravel-tags/pull/491
* @jordanmiguel made their first contribution in https://github.com/spatie/laravel-tags/pull/492

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.5.1...4.5.2

## 4.5.1 - 2023-07-31

### What's Changed

- Define class MorphPivot as default for the 'tags.taggable.class_name' config getter. by @eggnaube in https://github.com/spatie/laravel-tags/pull/476

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.5.0...4.5.1

## 4.5.0 - 2023-07-20

### What's Changed

- Bump dependabot/fetch-metadata from 1.4.0 to 1.5.0 by @dependabot in https://github.com/spatie/laravel-tags/pull/461
- Bump dependabot/fetch-metadata from 1.5.0 to 1.5.1 by @dependabot in https://github.com/spatie/laravel-tags/pull/462
- Bump dependabot/fetch-metadata from 1.5.1 to 1.6.0 by @dependabot in https://github.com/spatie/laravel-tags/pull/466
- changed deleteTags to deleteTag where appropriate by @titonova in https://github.com/spatie/laravel-tags/pull/469
- Add the ability to configure a custom Taggables model class by @eggnaube in https://github.com/spatie/laravel-tags/pull/470

### New Contributors

- @eggnaube made their first contribution in https://github.com/spatie/laravel-tags/pull/470

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.4.0...4.5.0

## 4.4.0 - 2023-05-16

### What's Changed

- Bump dependabot/fetch-metadata from 1.3.5 to 1.3.6 by @dependabot in https://github.com/spatie/laravel-tags/pull/444
- Bump dependabot/fetch-metadata from 1.3.6 to 1.4.0 by @dependabot in https://github.com/spatie/laravel-tags/pull/457
- Configable taggables morph table by @silnex in https://github.com/spatie/laravel-tags/pull/458

### New Contributors

- @silnex made their first contribution in https://github.com/spatie/laravel-tags/pull/458

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.3.7...4.4.0

## 4.3.7 - 2023-01-25

### What's Changed

- Return slugs in find methods by @falconsmilie in https://github.com/spatie/laravel-tags/pull/443

### New Contributors

- @falconsmilie made their first contribution in https://github.com/spatie/laravel-tags/pull/443

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.3.6...4.3.7

## 4.3.6 - 2023-01-24

- support Laravel 10

### What's Changed

- Add PHP 8.2 to tests workflow by @patinthehat in https://github.com/spatie/laravel-tags/pull/429
- Add Dependabot Automation by @patinthehat in https://github.com/spatie/laravel-tags/pull/430
- Match locale in example by @CaddyDz in https://github.com/spatie/laravel-tags/pull/435
- Bump actions/checkout from 2 to 3 by @dependabot in https://github.com/spatie/laravel-tags/pull/432
- Switch to anonymous migration and void return type for up method by @ziming in https://github.com/spatie/laravel-tags/pull/440
- Method for reversing migrations by @eimantaaas in https://github.com/spatie/laravel-tags/pull/442

### New Contributors

- @CaddyDz made their first contribution in https://github.com/spatie/laravel-tags/pull/435
- @dependabot made their first contribution in https://github.com/spatie/laravel-tags/pull/432
- @ziming made their first contribution in https://github.com/spatie/laravel-tags/pull/440
- @eimantaaas made their first contribution in https://github.com/spatie/laravel-tags/pull/442

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.3.5...4.3.6

## 4.3.5 - 2022-11-19

### What's Changed

- Add without tags scope by @stfndamjanovic in https://github.com/spatie/laravel-tags/pull/428

### New Contributors

- @stfndamjanovic made their first contribution in https://github.com/spatie/laravel-tags/pull/428

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.3.4...4.3.5

## 4.3.4 - 2022-11-17

### What's Changed

- change phpunit to pest testing framework by @uthadehikaru in https://github.com/spatie/laravel-tags/pull/423
- Fix typos in the docs by @ahmad-moussawi in https://github.com/spatie/laravel-tags/pull/425
- Fixed Collection support in syncTags function and added regression test by @zupolgec in https://github.com/spatie/laravel-tags/pull/427

### New Contributors

- @uthadehikaru made their first contribution in https://github.com/spatie/laravel-tags/pull/423
- @ahmad-moussawi made their first contribution in https://github.com/spatie/laravel-tags/pull/425

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.3.3...4.3.4

## 4.3.3 - 2022-10-21

### What's Changed

- Added support for strings and corresponding tests by @zupolgec in https://github.com/spatie/laravel-tags/pull/395

### New Contributors

- @zupolgec made their first contribution in https://github.com/spatie/laravel-tags/pull/395

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.3.2...4.3.3

## 4.3.2 - 2022-06-04

### What's Changed

- Added example of getting all the tags of a model by @titonova in https://github.com/spatie/laravel-tags/pull/394
- Update using-tags.md typo by changing "Find" to "find" by @jorgemurta in https://github.com/spatie/laravel-tags/pull/401
- readme: add example apply trait by @erlangparasu in https://github.com/spatie/laravel-tags/pull/403
- Update .gitattributes to decrease package installed size by @lakuapik in https://github.com/spatie/laravel-tags/pull/407

### New Contributors

- @titonova made their first contribution in https://github.com/spatie/laravel-tags/pull/394
- @jorgemurta made their first contribution in https://github.com/spatie/laravel-tags/pull/401
- @erlangparasu made their first contribution in https://github.com/spatie/laravel-tags/pull/403
- @lakuapik made their first contribution in https://github.com/spatie/laravel-tags/pull/407

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.3.1...4.3.2

## 4.3.1 - 2022-03-08

## What's Changed

- Fix BadMethodCallException caused by attach() by @umairparacha00 in https://github.com/spatie/laravel-tags/pull/389
- Ignore the `phpunit.xml` file by default by @markwalet in https://github.com/spatie/laravel-tags/pull/362
- Fixed the docs by @umairparacha00 in https://github.com/spatie/laravel-tags/pull/390
- Add support for spatie/laravel-translatable:^6.0 by @dsturm in https://github.com/spatie/laravel-tags/pull/391

## New Contributors

- @umairparacha00 made their first contribution in https://github.com/spatie/laravel-tags/pull/389
- @markwalet made their first contribution in https://github.com/spatie/laravel-tags/pull/362
- @dsturm made their first contribution in https://github.com/spatie/laravel-tags/pull/391

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.3.0...4.3.1

## 4.3.0 - 2022-01-14

- allow Laravel 9

## 4.2.1 - 2021-11-24

## What's Changed

- enable and fix test by @delta1186 in https://github.com/spatie/laravel-tags/pull/373
- Fix find from string of any type by @delta1186 in https://github.com/spatie/laravel-tags/pull/375

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.2.0...4.2.1

## 4.2.0 - 2021-11-22

## What's Changed

- Make convertToTags() method $values arg  support Tag instance by @chuoke in https://github.com/spatie/laravel-tags/pull/371

## New Contributors

- @chuoke made their first contribution in https://github.com/spatie/laravel-tags/pull/371

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.1.0...4.2.0

## 4.1.0 - 2021-11-17

## What's Changed

- Adding a static function for current locale to the tag by @leonidlezner in https://github.com/spatie/laravel-tags/pull/368

## New Contributors

- @leonidlezner made their first contribution in https://github.com/spatie/laravel-tags/pull/368

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.0.5...4.1.0

## 4.0.5 - 2021-11-10

## What's Changed

- PHP 8.1 Support by @sergiy-petrov in https://github.com/spatie/laravel-tags/pull/361
- Update installation-and-setup.md by @PatrickJunod in https://github.com/spatie/laravel-tags/pull/365
- Remove extra ordered on getWithType by @delta1186 in https://github.com/spatie/laravel-tags/pull/367

## New Contributors

- @sergiy-petrov made their first contribution in https://github.com/spatie/laravel-tags/pull/361
- @PatrickJunod made their first contribution in https://github.com/spatie/laravel-tags/pull/365
- @delta1186 made their first contribution in https://github.com/spatie/laravel-tags/pull/367

**Full Changelog**: https://github.com/spatie/laravel-tags/compare/4.0.4...4.0.5

## 4.0.4 - 2021-09-01

- get the tag model table name when syncing tags (#351)

## 4.0.3 -2021-04-28

- allow eloquent-sortable v4

## 4.0.2 - 2021-04-07

- update deps

## 4.0.1 - 2021-03-12

- fix translatable attributes (#300)

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
