# Changelog

All Notable changes to `laravel-tags` will be documented in this file

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
