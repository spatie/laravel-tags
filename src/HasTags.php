<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function tagsWithType($type): Collection
    {
        return $this->tags->filter(function (Tag $tag) use ($type) {
            return $tag->hasType($type);
        });
    }

    public function attachTags()
    {
        $this->tags()->detach();
    }


}
