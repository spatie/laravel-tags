<?php

namespace Spatie\Tags;

use ArrayAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use InvalidArgumentException;

trait HasTags
{
    protected array $queuedTags = [];

    public static function getTagClassName(): string
    {
        return config('tags.tag_model', Tag::class);
    }

    public static function getTagTableName(): string
    {
        $tagInstance = new (self::getTagClassName());

        return $tagInstance->getTable();
    }

    public static function getTagTablePrimaryKeyName(): string
    {
        return self::getTagTableName() . '.' . self::getTagPrimaryKey();
    }

    public static function getTagPrimaryKey(): string
    {
        $tagInstance = new (self::getTagClassName());

        return $tagInstance->getKeyName();
    }

    public function getTaggableMorphName(): string
    {
        return config('tags.taggable.morph_name', 'taggable');
    }

    public function getTaggableTableName(): string
    {
        return config('tags.taggable.table_name', 'taggables');
    }

    public function getPivotModelClassName(): string
    {
        return config('tags.taggable.class_name', MorphPivot::class);
    }

    public static function bootHasTags()
    {
        static::created(function (Model $taggableModel) {
            if (count($taggableModel->queuedTags) === 0) {
                return;
            }

            $taggableModel->attachTags($taggableModel->queuedTags);

            $taggableModel->queuedTags = [];
        });

        static::deleted(function (Model $deletedModel) {
            $tags = $deletedModel->tags()->get();

            $deletedModel->detachTags($tags);
        });
    }

    public function tags(): MorphToMany
    {
        return $this
            ->morphToMany(self::getTagClassName(), $this->getTaggableMorphName(), $this->getTaggableTableName())
            ->using($this->getPivotModelClassName())
            ->ordered();
    }

    public function tagsTranslated(string | null $locale = null): MorphToMany
    {
        $locale = ! is_null($locale) ? $locale : self::getTagClassName()::getLocale();

        return $this
            ->morphToMany(self::getTagClassName(), $this->getTaggableMorphName(), $this->getTaggableTableName())
            ->using($this->getPivotModelClassName())
            ->select('*')
            ->selectRaw($this->getQuery()->getGrammar()->wrap("name->{$locale} as name_translated"))
            ->selectRaw($this->getQuery()->getGrammar()->wrap("slug->{$locale} as slug_translated"))
            ->ordered();
    }

    public function setTagsAttribute(string | array | ArrayAccess | Tag $tags)
    {
        if (! $this->exists) {
            $this->queuedTags = $tags;

            return;
        }

        $this->syncTags($tags);
    }

    public function scopeWithAllTags(
        Builder $query,
        string | array | ArrayAccess | Tag $tags,
        ?string $type = null,
    ): Builder {
        $tags = static::convertToTags($tags, $type);

        collect($tags)->each(function ($tag) use ($query) {
            $query->whereHas('tags', function (Builder $query) use ($tag) {
                $query->where(self::getTagTablePrimaryKeyName(), $tag->id ?? 0);
            });
        });

        return $query;
    }

    public function scopeWithAnyTags(
        Builder $query,
        string | array | ArrayAccess | Tag $tags,
        ?string $type = null,
    ): Builder {
        $tags = static::convertToTags($tags, $type);

        return $query
            ->whereHas('tags', function (Builder $query) use ($tags) {
                $tagIds = collect($tags)->pluck('id');

                $query->whereIn(self::getTagTablePrimaryKeyName(), $tagIds);
            });
    }

    public function scopeWithoutTags(
        Builder $query,
        string | array | ArrayAccess | Tag $tags,
        ?string $type = null
    ): Builder {
        $tags = static::convertToTags($tags, $type);

        return $query
            ->whereDoesntHave('tags', function (Builder $query) use ($tags) {
                $tagIds = collect($tags)->pluck('id');

                $query->whereIn(self::getTagTablePrimaryKeyName(), $tagIds);
            });
    }

    public function scopeWithAllTagsOfAnyType(Builder $query, $tags): Builder
    {
        $tags = static::convertToTagsOfAnyType($tags);

        collect($tags)
            ->each(function ($tag) use ($query) {
                $query->whereHas(
                    'tags',
                    fn (Builder $query) => $query->where(self::getTagTablePrimaryKeyName(), $tag ? $tag->id : 0)
                );
            });

        return $query;
    }

    public function scopeWithAnyTagsOfAnyType(Builder $query, $tags): Builder
    {
        $tags = static::convertToTagsOfAnyType($tags);

        $tagIds = collect($tags)->pluck('id');

        return $query->whereHas(
            'tags',
            fn (Builder $query) => $query->whereIn(self::getTagTablePrimaryKeyName(), $tagIds)
        );
    }

    public function scopeWithAnyTagsOfType(Builder $query, string|array $type): Builder
    {
        return $query->whereHas(
            'tags',
            fn (Builder $query) => $query->whereIn('type', (array) $type)
        );
    }

    public function tagsWithType(?string $type = null): Collection
    {
        return $this->tags->filter(fn (Tag $tag) => $tag->type === $type);
    }

    public function attachTags(array | ArrayAccess | Tag $tags, ?string $type = null): static
    {
        $className = static::getTagClassName();

        $tags = collect($className::findOrCreate($tags, $type));

        $this->tags()->syncWithoutDetaching($tags->pluck('id')->toArray());

        return $this;
    }

    public function attachTag(string | Tag $tag, string | null $type = null)
    {
        return $this->attachTags([$tag], $type);
    }

    public function detachTags(array | ArrayAccess $tags, string | null $type = null): static
    {
        $tags = static::convertToTags($tags, $type);

        collect($tags)
            ->filter()
            ->each(fn (Tag $tag) => $this->tags()->detach($tag));

        return $this;
    }

    public function detachTag(string | Tag $tag, string | null $type = null): static
    {
        return $this->detachTags([$tag], $type);
    }

    public function syncTags(string | array | ArrayAccess $tags): static
    {
        if (is_string($tags)) {
            $tags = Arr::wrap($tags);
        }

        $className = static::getTagClassName();

        $tags = collect($className::findOrCreate($tags));

        $this->tags()->sync($tags->pluck('id')->toArray());

        return $this;
    }

    public function syncTagsWithType(array | ArrayAccess $tags, string | null $type = null): static
    {
        $className = static::getTagClassName();

        $tags = collect($className::findOrCreate($tags, $type));

        $this->syncTagIds($tags->pluck('id')->toArray(), $type);

        return $this;
    }

    protected static function convertToTags($values, $type = null, $locale = null)
    {
        if ($values instanceof Tag) {
            $values = [$values];
        }

        return collect($values)->map(function ($value) use ($type, $locale) {
            if ($value instanceof Tag) {
                if (isset($type) && $value->type != $type) {
                    throw new InvalidArgumentException("Type was set to {$type} but tag is of type {$value->type}");
                }

                return $value;
            }

            $className = static::getTagClassName();

            return $className::findFromString($value, $type, $locale);
        });
    }

    protected static function convertToTagsOfAnyType($values, $locale = null)
    {
        return collect($values)->map(function ($value) use ($locale) {
            if ($value instanceof Tag) {
                return $value;
            }

            $className = static::getTagClassName();

            return $className::findFromStringOfAnyType($value, $locale);
        })->flatten();
    }

    protected function syncTagIds($ids, string | null $type = null, $detaching = true): void
    {
        $isUpdated = false;

        $tagModel = $this->tags()->getRelated();

        // Get a list of tag_ids for all current tags
        $current = $this->tags()
            ->newPivotStatement()
            ->where($this->getTaggableMorphName() . '_id', $this->getKey())
            ->where($this->getTaggableMorphName() . '_type', $this->getMorphClass())
            ->join(
                $tagModel->getTable(),
                $this->getTaggableTableName() . '.tag_id',
                '=',
                $tagModel->getTable() . '.' . $tagModel->getKeyName()
            )
            ->where($tagModel->getTable() . '.type', $type)
            ->pluck('tag_id')
            ->all();

        // Compare to the list of ids given to find the tags to remove
        $detach = array_diff($current, $ids);
        if ($detaching && count($detach) > 0) {
            $this->tags()->detach($detach);
            $isUpdated = true;
        }

        // Attach any new ids
        $attach = array_unique(array_diff($ids, $current));
        if (count($attach) > 0) {
            $this->tags()->attach($attach, []);

            $isUpdated = true;
        }

        // Once we have finished attaching or detaching the records, we will see if we
        // have done any attaching or detaching, and if we have we will touch these
        // relationships if they are configured to touch on any database updates.
        if ($isUpdated) {
            $this->tags()->touchIfTouching();
        }
    }

    public function hasTag($tag, ?string $type = null): bool
    {
        return $this->tags
            ->when($type !== null, fn ($query) => $query->where('type', $type))
            ->contains(function ($modelTag) use ($tag) {
                return $modelTag->name === $tag || $modelTag->id === $tag;
            });
    }
}
