<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    public function tags(): MorphToMany
    {
        return $this
            ->morphToMany(Tag::class, 'taggable')
            ->orderBy('order_column');
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
        if (! $this->isIterable($tags)) {
            $tags = [$tags];
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
        if (! $this->isIterable($tags)) {
            $tags = [$tags];
        }

        $tags = Tag::findOrCreate($tags);

        collect($tags)->each(function (Tag $tag) {
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

    /**
     * @param mixed $value
     * @return bool
     */
    protected function isIterable($value): bool
    {
        if (is_array($value)) {
            return true;
        }

        return ($value instanceof ArrayAccess);
    }
}
