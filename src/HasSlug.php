<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::saving(function (Model $model) {
            $model->slug = $model->generateSlug();
        });
    }

    protected function generateSlug(): string
    {
        $slugger = config('tags.slugger');

        $slugger = $slugger ?: 'str_slug';

        return call_user_func($slugger, $this->name);
    }
}
