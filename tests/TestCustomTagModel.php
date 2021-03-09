<?php

namespace Spatie\Tags\Test;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\Tag;

class TestCustomTagModel extends Tag
{
    public $table = 'custom_tags';

    public $translatable = ['name', 'slug', 'description'];
}
