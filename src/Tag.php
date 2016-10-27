<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Collection as DbCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;

class Tag extends Model implements Sortable
{
    use SortableTrait, HasTranslations, HasSlug;

    public $translatable = ['name', 'slug'];

    public $guarded = [];

    public function scopeWithType(Builder $query, string $type = null): Builder
    {if (is_null($type)) {
            return $query;
        }

        return $query->where('type', $type)->orderBy('order_column');
    }


    /**
     * @param array|\ArrayAccess $values
     * @param string|null $type
     * @param string|null $locale
     *
     * @return \Spatie\Tags\Tag|static
     */
    public static function findOrCreate($values, string $type = null, string $locale = null)
    {

        $tags = collect($values)->map(function (string $value) use ($type, $locale) {

            if ($value instanceof Tag) {
                return $value;
            }

            return static::findOrCreateFromString($value, $type, $locale);
        });

        return is_string($values) ? $tags->first() : $tags;
    }

    public static function getWithType(string $type): DbCollection
    {
        return static::type($type)->get();
    }

    public static function findFromString(string $name, string $type = null, string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return static::query()
            ->where("name->{$locale}", $name)
            ->where('type', $type)
            ->first();
    }

    protected static function findOrCreateFromString(string $name, string $type = null, string $locale = null): Tag
    {

        $locale = $locale ?? app()->getLocale();

        $tag = static::findFromString($name, $type, $locale);

        if (! $tag) {
            $tag = static::create([
                'name' => [$locale => $name],
                'type' => $type,
            ]);
        }
        return $tag;
    }
}
