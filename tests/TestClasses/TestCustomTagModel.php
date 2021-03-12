<?php

namespace Spatie\Tags\Test\TestClasses;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\Tag;

class TestCustomTagModel extends Tag
{
    public $table = 'custom_tags';

    public array $translatable = ['name', 'slug', 'description'];
}
