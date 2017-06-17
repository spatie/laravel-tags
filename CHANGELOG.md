# Changelog

All notable changes to `laravel-tags` will be documented in this file

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
