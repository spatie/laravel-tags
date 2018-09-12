<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected function generateSlug(string $locale): string
    {
        $slugger = config('tags.slugger');

        $slugger = $slugger ?: 'str_slug';

        return call_user_func($slugger, $this->name);
    }
}
