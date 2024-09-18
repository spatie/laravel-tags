<?php

namespace Spatie\Tags\Test\TestClasses;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Spatie\Tags\TaggedToMany;

class TestModel extends Model
{
    use HasTags;

    public $table = 'test_models';

    protected $guarded = [];

    public $timestamps = false;

    public function anotherModels(): TaggedToMany
    {
        return $this->taggedToMany(TestAnotherModel::class);
    }

    public function anotherModelsOfType(): TaggedToMany
    {
        return $this->taggedToMany(TestAnotherModel::class, "test-type");
    }
}
