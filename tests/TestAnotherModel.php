<?php

namespace Spatie\Tags\Test;

use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\Model;

class TestAnotherModel extends Model
{
    use HasTags;

    public $table = 'test_another_models';

    protected $guarded = [];

    public $timestamps = false;
}
