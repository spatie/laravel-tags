<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Collection as DbCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;

class Tag extends Model implements Sortable
{
    use SortableTrait, HasTranslations;

    public $translatable = ['name', 'url'];

    public $guarded = [];

    public function scopeType(Builder $query, string $type = null): Builder
    {
        if (is_null($type)) {
            return $query;
        }

        return $query->where('type', $type)->orderBy('order_column');
    }

    public static function getWithType($type): DbCollection
    {
        return static::type($type)->get();
    }

    /**
     * @param $values
     * @param string|null $type
     * @param string|null $locale
     *
     * @return \Spatie\Tags\Tag|static
     */
    public static function findOrCreate($values, string $type = null, string $locale = null)
    {
        $tags = collect($values)->map(function (string $value) use ($type, $locale) {
            return static::findOrCreateFromString($value, $type, $locale);
        });

        return is_string($values) ? $tags->first() : $tags;
    }

    protected static function findOrCreateFromString(string $name, string $type = null, string $locale = null): Tag
    {
        $locale = $locale ?? app()->getLocale();

        $tag = static::query()
            ->where("name->{$locale}", $name)
            ->where('type', $type)
            ->first();

        if (!$tag) {
            $tag = static::create([
                'name' => [$locale => $name],
                'type' => $type
            ]);
        }

        return $tag;
    }


}
