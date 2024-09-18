<?php

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Tags\Tag;
use Spatie\Tags\Test\TestClasses\TestAnotherModel;
use Spatie\Tags\Test\TestClasses\TestModel;

beforeEach(function () {
    $this->testParentModel = TestModel::create(['name' => 'parent']);
    $this->testRelatedModel_1 = TestAnotherModel::create(['name' => 'model 1']);
    $this->testRelatedModel_2 = TestAnotherModel::create(['name' => 'model 2']);
});


it('should return other model with same tags', function () {
    $testTag = "Test Tag";

    $this->testParentModel->attachTag($testTag);
    $this->testRelatedModel_2->attachTag($testTag);

    $res = $this->testParentModel->anotherModels;
    $this->assertCount(1, $res);
    $this->assertEquals($this->testRelatedModel_2->id, $res->first()->id);
});

it('should not return other models without same tags', function () {
    $testTag = "Test Tag";
    $testOtherTag = "Other Tag";

    $this->testParentModel->attachTag($testTag);
    $this->testRelatedModel_2->attachTag($testOtherTag);

    $res = $this->testParentModel->anotherModels;
    $this->assertCount(0, $res);
});

it('should return other model with same tags and of same type', function () {
    $testTag = "Test Tag";

    $this->testParentModel->attachTag($testTag, "test-type");
    $this->testRelatedModel_2->attachTag($testTag, "test-type");

    $res = $this->testParentModel->anotherModelsOfType;
    $this->assertCount(1, $res);
    $this->assertEquals($this->testRelatedModel_2->id, $res->first()->id);
});

it('should not return other model with same tags and of different type', function () {
    $testTag = "Test Tag";

    $this->testParentModel->attachTag($testTag, "test-type");
    $this->testRelatedModel_2->attachTag($testTag, "other-type");

    $res = $this->testParentModel->anotherModelsOfType;
    $this->assertCount(0, $res);
});
