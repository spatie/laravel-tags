<?php

namespace Spatie\Tags;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    protected $queuedTags = [];

    public static function getTagClassName(): string
    {
        return Tag::class;
    }

    public static function bootHasTags()
    {
        static::created(function (Model $taggableModel) {
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
            ->morphToMany(self::getTagClassName(), 'taggable')
            ->orderBy('order_column');
    }

    /**
     * @param string|array|\ArrayAccess|\Spatie\Tags\Tags $tags
     */
    public function setTagsAttribute($tags)
    {
        if (! $this->exists) {
            $this->queuedTags = $tags;

            return;
        }

        $this->attachTags($tags);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|\ArrayAccess|\Spatie\Tags\Tag   $tags
     * @param string|null                           $type
     * @param bool                                  $not
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAllTags(Builder $query, $tags, string $type = null, bool $not = false): Builder
    {
        $method = $not ? 'orWhereDoesntHave' : 'whereHas';

        $tags = static::convertToTags($tags, $type);

        collect($tags)->each(function ($tag) use ($method, $query) {
            $query->{$method}('tags', function (Builder $query) use ($tag) {
                return $query->where('id', $tag ? $tag->id : 0);
            });
        });

        return $query;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|\ArrayAccess|\Spatie\Tags\Tag   $tags
     * @param string|null                           $type
     * @param bool                                  $not
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyTags(Builder $query, $tags, string $type = null, bool $not = false): Builder
    {
        $method = $not ? 'whereDoesntHave' : 'whereHas';

        $tags = static::convertToTags($tags, $type);

        return $query->{$method}('tags', function (Builder $query) use ($tags) {
            $tagIds = collect($tags)->pluck('id');

            $query->whereIn('id', $tagIds);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|\ArrayAccess|\Spatie\Tags\Tag   $tags
     * @param string|null                           $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutAllTags(Builder $query, $tags, string $type = null): Builder
    {
        return $this->scopeWithAllTags($query, $tags, $type, true);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|\ArrayAccess|\Spatie\Tags\Tag   $tags
     * @param string|null                           $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutAnyTags(Builder $query, $tags, string $type = null): Builder
    {
        return $this->scopeWithAnyTags($query, $tags, $type, true);
    }

    public function tagsWithType(string $type = null): Collection
    {
        return $this->tags->filter(function (Tag $tag) use ($type) {
            return $tag->type === $type;
        });
    }

    /**
     * @param array|\ArrayAccess|\Spatie\Tags\Tag $tags
     *
     * @return $this
     */
    public function attachTags($tags)
    {
        $className = static::getTagClassName();

        $tags = collect($className::findOrCreate($tags));

        $this->tags()->syncWithoutDetaching($tags->pluck('id')->toArray());

        return $this;
    }

    /**
     * @param string|\Spatie\Tags\Tag $tag
     *
     * @return $this
     */
    public function attachTag($tag)
    {
        return $this->attachTags([$tag]);
    }

    /**
     * @param array|\ArrayAccess $tags
     *
     * @return $this
     */
    public function detachTags($tags)
    {
        $tags = static::convertToTags($tags);

        collect($tags)
            ->filter()
            ->each(function (Tag $tag) {
                $this->tags()->detach($tag);
            });

        return $this;
    }

    /**
     * @param string|\Spatie\Tags\Tag $tag
     *
     * @return $this
     */
    public function detachTag($tag)
    {
        return $this->detachTags([$tag]);
    }

    /**
     * @param array|\ArrayAccess $tags
     *
     * @return $this
     */
    public function syncTags($tags)
    {
        $className = static::getTagClassName();

        $tags = collect($className::findOrCreate($tags));

        $this->tags()->sync($tags->pluck('id')->toArray());

        return $this;
    }

    /**
     * @param array|\ArrayAccess $tags
     * @param string|null $type
     *
     * @return $this
     */
    public function syncTagsWithType($tags, string $type = null)
    {
        $className = static::getTagClassName();

        $tags = collect($className::findOrCreate($tags, $type));

        $this->syncTagIds($tags->pluck('id')->toArray(), $type);

        return $this;
    }

    protected static function convertToTags($values, $type = null, $locale = null)
    {
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

    /**
     * Use in place of eloquent's sync() method so that the tag type may be optionally specified.
     *
     * @param $ids
     * @param string|null $type
     * @param bool $detaching
     */
    protected function syncTagIds($ids, string $type = null, $detaching = true)
    {
        $isUpdated = false;

        // Get a list of tag_ids for all current tags
        $current = $this->tags()
            ->newPivotStatement()
            ->where('taggable_id', $this->getKey())
            ->when($type !== null, function ($query) use ($type) {
                $tagModel = $this->tags()->getRelated();

                return $query->join(
                    $tagModel->getTable(),
                    'taggables.tag_id',
                    '=',
                    $tagModel->getTable().'.'.$tagModel->getKeyName()
                )
                    ->where('tags.type', $type);
            })
            ->pluck('tag_id')
            ->all();

        // Compare to the list of ids given to find the tags to remove
        $detach = array_diff($current, $ids);
        if ($detaching && count($detach) > 0) {
            $this->tags()->detach($detach);
            $isUpdated = true;
        }

        // Attach any new ids
        $attach = array_diff($ids, $current);
        if (count($attach) > 0) {
            collect($attach)->each(function ($id) {
                $this->tags()->attach($id, []);
            });
            $isUpdated = true;
        }

        // Once we have finished attaching or detaching the records, we will see if we
        // have done any attaching or detaching, and if we have we will touch these
        // relationships if they are configured to touch on any database updates.
        if ($isUpdated) {
            $this->tags()->touchIfTouching();
        }
    }
}
