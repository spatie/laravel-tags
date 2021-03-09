<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::saving(function (Model $model) {
            collect($model->getTranslatedLocales('name'))
                ->each(fn (string $locale) => $model->setTranslation(
                    'slug',
                    $locale,
                    $model->generateSlug($locale)
                ));
        });
    }

    protected function generateSlug(string $locale): string
    {
        $slugger = config('tags.slugger');

        $slugger ??= '\Illuminate\Support\Str::slug';

        return call_user_func($slugger, $this->getTranslation('name', $locale));
    }
}
