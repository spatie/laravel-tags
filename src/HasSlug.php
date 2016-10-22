<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::saving(function (Model $model) {
            $model->attributes['slug'] = $model->generateSlug();
        });
    }

    protected function generateSlug(): string
    {
        $slugs = [];

        foreach ($this->getTranslatedLocales('name') as $locale) {
            $slugs[$locale] = str_slug($this->getTranslation('name', $locale));
        }

        return json_encode($slugs);
    }
}