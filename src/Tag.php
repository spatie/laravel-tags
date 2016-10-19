<?php

namespace App\Tags;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;

class Tag extends Model implements Sortable
{
    use SortableTrait, HasTranslations;

    public $translatable = ['name', 'url'];

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

    public static function findByNameOrCreate(string $name, $type = '', $locale = ''): Tag
    {
        $tag = static::query()
            ->where('name', 'regexp', "\"{$locale}\"\s*:\s*\"{$name}\"")
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
