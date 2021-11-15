<?php

namespace Spatie\Tags\Test\TestClasses;

use Spatie\Tags\Tag;

class TestCustomTagStaticLocaleModel extends Tag
{
    public $table = 'custom_tags_static_locale';

    public static function getLocale()
    {
        return 'en';
    }
}
