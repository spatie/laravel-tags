<?php

namespace Spatie\Tags;

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
        $normalizer = config('tags.normalizer');
        $normalizer = $normalizer ?: 'str_slug';

        return call_user_func($normalizer, $this->getTranslation('name', $locale));
    }
}
