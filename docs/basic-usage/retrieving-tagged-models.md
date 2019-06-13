---
title: Retrieving tagged models
weight: 2
---

The package provides four scopes `withAnyTags`, `withAllTags`, `withAnyTagsOfAnyType`, and `withAllTagsOfAnyType` that can help you find models with certain tags.

### withAnyTags

The `withAnyTags` scope will return models that have one or more of the given tags attached to them. If you pass the `type` argument, it will look for tags with specified type, if not, it will only look for tags that have no type.

```php
//returns models that have one or more of the given tags that are not saved with a type
YourModel::withAnyTags(['tag 1', 'tag 2'])->get();

//returns models that have one or more of the given tags that are typed `myType`
YourModel::withAnyTags(['tag 1', 'tag 2'], 'myType')->get();
```

### withAllTags

The `withAllTags` scope will return only the models that have all of the given tags attached to them. If you pass the `type` argument, it will look for tags with specified type, if not, it will only look for tags that have no type. So when passing a non-existing tag, or a correct tag name with the wrong type, no models will be returned.

```
//returns models that have all given tags that are not saved with a type
YourModel::withAllTags(['tag 1', 'tag 2'])->get();

//returns models that have all given tags that are typed `myType`
YourModel::withAllTags(['tag 1', 'tag 2'], 'myType')->get();
```

### withAnyTagsOfAnyType

The `withAnyTagsOfAnyType` scope will return models that have one or more of the given tags attached to them, but doesn't restrict given tags to any type if they are passed as `string`.

```php
//returns models that have one or more of the given tags of any type
YourModel::withAnyTagsOfAnyType(['tag 1', 'tag 2'])->get();
```

### withAllTagsOfAnyType

The `withAllTagsOfAnyType` scope will return only the models that have all of the given tags attached to them, but doesn't restrict given tags to any type if they are passed as `string`. So when passing a non-existing tag no models will be returned.

```
//returns models that have all given tags of any type
YourModel::withAllTagsOfAnyType(['tag 1', 'tag 2'])->get();
```
