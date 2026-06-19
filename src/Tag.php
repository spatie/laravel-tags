<?php

namespace Spatie\Tags;

use ArrayAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as DbCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;

class Tag extends Model implements Sortable
{
    use SortableTrait;
    use HasTranslations;
    use HasSlug;
    use HasFactory;

    public array $translatable = ['name', 'slug'];

    public $guarded = [];

    public static function getLocale()
    {
        return app()->getLocale();
    }

    public function scopeWithType(Builder $query, ?string $type = null): Builder
    {
        if (is_null($type)) {
            return $query;
        }

        return $query->where('type', $type)->ordered();
    }

    public function scopeContaining(Builder $query, string $name, $locale = null): Builder
    {
        $locale = $locale ?? static::getLocale();

        return $query->whereRaw('lower(' . $this->getQuery()->getGrammar()->wrap('name->' . $locale) . ') like ?', ['%' . mb_strtolower($name) . '%']);
    }

    public static function findOrCreate(
        string | array | ArrayAccess $values,
        string | null $type = null,
        string | null $locale = null,
    ): Collection | Tag | static {
        $locale = $locale ?? static::getLocale();

        $valueCollection = collect($values);

        // Grab the plain string values (anything that isn't already a Tag) so we
        // can resolve them all in a single query instead of one query per value.
        $names = $valueCollection
            ->reject(fn ($value) => $value instanceof self)
            ->all();

        $existingTags = static::findManyFromString($names, $type, $locale);

        $tags = $valueCollection->map(function ($value) use ($existingTags, $type, $locale) {
            // A Tag instance was passed in directly, so there's nothing to look up.
            if ($value instanceof self) {
                return $value;
            }

            // Reuse a tag we already fetched (or created earlier in this loop).
            $tag = $existingTags->first(fn (self $existingTag) => $existingTag->matchesString($value, $locale));

            if (! $tag) {
                $tag = static::create([
                    'name' => [$locale => $value],
                    'type' => $type,
                ]);

                // Push it so a later duplicate value in the same call reuses this
                // tag instead of creating another row for it.
                $existingTags->push($tag);
            }

            return $tag;
        });

        return is_string($values) ? $tags->first() : $tags;
    }

    public static function getWithType(string $type): DbCollection
    {
        return static::withType($type)->get();
    }

    public static function findFromString(string $name, ?string $type = null, ?string $locale = null)
    {
        return static::findManyFromString([$name], $type, $locale)->first();
    }

    public static function findManyFromString(array $names, ?string $type = null, ?string $locale = null): DbCollection
    {
        $locale = $locale ?? static::getLocale();

        if (blank($names)) {
            return DbCollection::make();
        }

        return static::query()
            ->where('type', $type)
            ->where(function ($query) use ($names, $locale) {
                $query->whereIn("name->{$locale}", $names)
                    ->orWhereIn("slug->{$locale}", $names);
            })
            ->get();
    }

    public function matchesString(string $name, ?string $locale = null): bool
    {
        $locale = $locale ?? static::getLocale();

        return $this->getTranslation('name', $locale, false) === $name
            || $this->getTranslation('slug', $locale, false) === $name;
    }

    public static function findFromStringOfAnyType(string $name, ?string $locale = null)
    {
        $locale = $locale ?? static::getLocale();

        return static::query()
            ->where("name->{$locale}", $name)
            ->orWhere("slug->{$locale}", $name)
            ->get();
    }

    public static function findOrCreateFromString(string $name, ?string $type = null, ?string $locale = null)
    {
        $locale = $locale ?? static::getLocale();

        $tag = static::findFromString($name, $type, $locale);

        if (! $tag) {
            $tag = static::create([
                'name' => [$locale => $name],
                'type' => $type,
            ]);
        }

        return $tag;
    }

    public static function getTypes(): Collection
    {
        return static::groupBy('type')->orderBy('type')->pluck('type');
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->translatable) && ! is_array($value)) {
            return $this->setTranslation($key, static::getLocale(), $value);
        }

        return parent::setAttribute($key, $value);
    }
}
