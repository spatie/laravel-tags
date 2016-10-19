<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;

class Tag extends Model implements Sortable
{
    use SortableTrait, HasTranslations;

    public $translatable = ['name', 'url'];

    public $guarded = [];

    public function scopeWithType($query, $type)
    {
        return $query
            ->where('type', $type)
            ->orderBy('order_column');
    }

    public static function getWithType($type): Collection
    {
        return static::type($type)->get();
    }

    public static function fromString(string $name, $type = '', $locale = null): Tag
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

    public function scopeType(Builder $query, $type = null): Builder
    {
        if (is_null($type)) {
            return $query;
        }

        return $query->where('type', $type);
    }
}
