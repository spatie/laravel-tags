<?php

use Spatie\Tags\Test\TestClasses\TestModel;

beforeEach(function () {
    $this->testModel = TestModel::create(['name' => 'default']);
});

it('provides models with tag name and slug already translated', function () {
    $this->testModel->attachTag('My Tag');

    $locale = app()->getLocale();
    $translated = $this->testModel->tagsTranslated->first()->toArray();

    expect($translated['name'][$locale])->toEqual($translated['name_translated']);
    expect($translated['slug'][$locale])->toEqual($translated['slug_translated']);
});


it('can provide models with tag name and slug translated for alternate locales', function () {
    $this->testModel->attachTag('My Tag');

    $locale = 'fr';

    $tag = $this->testModel->tags->first();
    $tag->setTranslation('name', $locale, 'Mon tag');
    $tag->save();

    $translated = $this->testModel->tagsTranslated($locale)->first()->toArray();

    expect($translated['name'][$locale])->toEqual($translated['name_translated']);
    expect($translated['slug'][$locale])->toEqual($translated['slug_translated']);
});

it('retrieves English tags with tagsTranslated method regardless of application locale', function () {
    $testModel = TestModel::create(['name' => 'default']);
    $testModel->attachTag('Test Tag');

    app()->setLocale('fr');

    $tags = $testModel->tagsTranslated('en')->get();
    expect($tags->first()->name_translated)->toEqual('Test Tag');
});

it('filters models with any English tags regardless of application locale', function () {
    $testModel = TestModel::create(['name' => 'default']);
    $testModel->attachTag('Test Tag');

    app()->setLocale('fr');

    $modelsWithEnglishTag = TestModel::withAnyTags('Test Tag', null, 'en')->get();
    expect($modelsWithEnglishTag)->toHaveCount(1);
    expect($modelsWithEnglishTag->first()->name)->toEqual('default');
});

it('filters models with all specified English tags regardless of application locale', function () {
    $testModel = TestModel::create(['name' => 'default']);
    $testModel->attachTag('Test Tag');
    $testModel->attachTag('Another Tag');

    app()->setLocale('fr');

    $modelsWithAllTags = TestModel::withAllTags(['Test Tag', 'Another Tag'], null, 'en')->get();
    expect($modelsWithAllTags)->toHaveCount(1);
    expect($modelsWithAllTags->first()->name)->toEqual('default');
});

it('excludes models with specified English tags regardless of application locale', function () {
    TestModel::create(['name' => 'other'])->attachTag('Different Tag');
    $testModel = TestModel::create(['name' => 'default']);
    $testModel->attachTag('Test Tag');

    app()->setLocale('fr');

    $modelsWithoutTag = TestModel::withoutTags('Test Tag', null, 'en')->get();
    expect($modelsWithoutTag)->each->not->toHaveProperty('name', 'default');
});
