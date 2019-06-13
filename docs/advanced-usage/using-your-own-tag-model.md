---
title: Using your own tag model
weight: 4
---

You might want to override some functionality of the `Spatie\Tags\Tag` or add some extra functions. It's very easy to use your own custom tag model. All you need to do is override the `getTagClassName` method from the `HasTags` trait. It should return the fully qualified class name of an eloquent model that extends `Spatie\Tags\Tag` and uses the same `tags` table.

```php
use Illuminate\Database\Eloquent\Model;

class YourModel extends Model
{
    public static function getTagClassName(): string
    {
        return YourTagModel::class;
    }
}
```

Then you need to override the `tags()` method from the same trait to tell Laravel that it still needs to look for `tags_id` column for tags relation instead of `your_tag_model_id`:

```
use Illuminate\Database\Eloquent\Relations\MorphToMany;

public function tags(): MorphToMany
{
    return $this
        ->morphToMany(self::getTagClassName(), 'taggable', 'taggables', null, 'tag_id')
        ->orderBy('order_column');
}
```
