<?php

namespace Spatie\Tags\Test\TestClasses;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class TestAnotherModel extends Model
{
    use HasTags;

    public $table = 'test_another_models';

    protected $guarded = [];

    public $timestamps = false;
}
