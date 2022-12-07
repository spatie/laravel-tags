---
title: Using another default locale
weight: 7
---

Imagine you have a multi-language application. The admin section has English set as the app's locale, but you want tags to be input by your admins in another language by default, for instance Dutch.

In this case, you can set the default language for tags to Dutch.

First create, your own `Tag` model and override the function `getLocale()`. 

```php
namespace App\Models;

use Spatie\Tags\Tag as SpatieTag;

class YourTag extends SpatieTag
{
    public static function getLocale(): string
    {
        return 'nl';
    }
}
```

Next, change the default `Tag` class in tags config (`config/tags.php`):

```php
return [

    /*
     * The given function generates a URL friendly "slug" from the tag name property before saving it.
     * Defaults to Str::slug (https://laravel.com/docs/master/helpers#method-str-slug)
     */
    'slugger' => null,

    /*
     * The fully qualified class name of the tag model.
     */
    'tag_model' => App\Models\YourTag::class,
];
```
