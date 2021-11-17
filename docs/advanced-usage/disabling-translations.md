---
title: Disabling translations
weight: 2
---

In some cases, like setting tags by page visitors in a multi-language environment, the translations are not needed and can even cause "empty" tags. Tag's information like name and description shall contain the same information regardless the current locale, during creating and displaying the tag.

To achieve this behavior you can force the Tag class to use a specific locale rather than getting the current application's locale. First create an own Tag's model and override the function `getLocale()`. 

```php

namespace App\Models;

use Spatie\Tags\Tag as SpatieTag;

class YourTag extends SpatieTag
{
    public static function getLocale()
    {
        return 'en';
    }
}

```

Then don't forget to change the tag class in tags config (config/tags.php):

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