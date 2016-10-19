<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    protected $queuedTags = [];

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
            ->morphToMany(Tag::class, 'taggable')
            ->orderBy('order_column');
    }

    /**
     * @param string|array|\ArrayAccess|\Spatie\Tags\Tags $tags
     */
    public function setTagsAttribute($tags)
    {
        if (!$this->exists) {
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
    public function scopeWithAllTags(Builder $query, $tags): Builder
    {
        if (!is_array($tags)) {
            $tags = [$tags];
        }

        if (!count($tags)) {
            return $query;
        }

        $tags = Tag::find($tags);

        collect($tags)->each(function ($tag) use ($query) {
            $query->whereHas('tags', function (Builder $query) use ($tag) {
                return $query->where('id', $tag ? $tag->id : 0);
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
    public function scopeWithAnyTags(Builder $query, $tags): Builder
    {
        if (!is_array($tags)) {
            $tags = [$tags];
        }

        if (!count($tags)) {
            return $query;
        }

        $tags = Tag::find($tags);

        return $query->whereHas('tags', function (Builder $query) use ($tags) {
            $tagIds = collect($tags)->map(function ($tag) {
                return $tag ? $tag->id : 0;
            })->toArray();

            $query->whereIn('id', $tagIds);
        });
    }

    public function tagsOfType(string $type = null): Collection
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
        if (!is_array($tags)) {
            $tags = [$tags];
        }

        if (!count($tags)) {
            return $this;
        }

        $tags = Tag::findOrCreate($tags);

        collect($tags)->each(function (Tag $tag) {
            $this->tags()->attach($tag);
        });

        return $this;
    }

    /**
     * @param array|\ArrayAccess|\Spatie\Tags\Tag $tags
     *
     * @return $this
     */
    public function attachTag($tags)
    {
        return $this->attachTags($tags);
    }

    /**
     * @param array|\ArrayAccess|\Spatie\Tags\Tag $tags
     *
     * @return $this
     */
    public function detachTags($tags)
    {
        if (!is_array($tags)) {
            $tags = [$tags];
        }

        $tags = Tag::find($tags);

        collect($tags)
            ->filter()
            ->each(function (Tag $tag) {
                $this->tags()->detach($tag);
            });

        return $this;
    }

    /**
     * @param array|\ArrayAccess|\Spatie\Tags\Tag $tags
     *
     * @return $this
     */
    public function detachTag($tags)
    {
        return $this->detachTags($tags);
    }

    /**
     * @param array|\ArrayAccess $tags
     *
     * @return $this
     */
    public function syncTags($tags)
    {
        $tags = Tag::findOrCreate($tags);

        $this->tags()->sync($tags);

        return $this;
    }
}
