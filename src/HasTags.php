<?php

namespace Spatie\Tags;

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
     * @param array|\ArrayAccess|\Spatie\Tags\Tags $tags
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAllTags(Builder $query, $tags, string $type = null): Builder
    {
        $tags = static::convertToTags($tags, $type);

        collect($tags)->each(function ($tag) use ($query, $type) {
            $query->whereHas('tags', function (Builder $query) use ($tag, $type) {
                return $query->where('id', $tag ? $tag->id : 0)->where('tags.type', $type);
            });
        });

        return $query;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|\ArrayAccess|\Spatie\Tags\Tags $tags
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyTags(Builder $query, $tags, string $type = null): Builder
    {
        $tags = static::convertToTags($tags, $type);

        return $query->whereHas('tags', function (Builder $query) use ($tags, $type) {
            $tagIds = collect($tags)->pluck('id');

            $query->whereIn('id', $tagIds)->where('tags.type', $type);
        });
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

    protected static function convertToTags($values, $type = null, $locale = null)
    {
        return collect($values)->map(function ($value) use ($type, $locale) {
            if ($value instanceof Tag) {
                return $value;
            }

            $className = static::getTagClassName();

            return $className::findFromString($value, $type, $locale);
        });
    }
}
