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

    public function tagsOfType($type = null): Collection
    {
        return $this->tags->filter(function(Tag $tag) use ($type) {
           return $tag->type === $type;
        });
    }
}
