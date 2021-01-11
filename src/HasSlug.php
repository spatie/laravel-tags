<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        return Str::slug($this->getTranslation('name', $locale));
    }
}
