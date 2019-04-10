<?php

namespace Spatie\Tags;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::saving(function (Model $model) {
            collect($model->getTranslatedLocales('name'))
                ->each(function (string $locale) use ($model) {
                    $model->setTranslation('slug', $locale, $model->generateSlug($locale));
                });
        });
    }

    protected function generateSlug(string $locale): string
    {
        $slugger = config('tags.slugger');

        $slugger = $slugger ?: 'Str::slug';

        return call_user_func($slugger, $this->getTranslation('name', $locale));
    }
}
