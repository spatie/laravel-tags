<?php

use Spatie\Tags\Test\TestClasses\TestModel;

beforeEach(function () {
    $this->testModel = TestModel::create(['name' => 'default']);
});

it('provides models with tag name and slug already translated',function()
{
    $this->testModel->attachTag('My Tag');

    $locale = app()->getLocale();
    $translated = $this->testModel->tagsTranslated->first()->toArray();

    $this->assertEquals($translated['name_translated'], $translated['name'][$locale]);
    $this->assertEquals($translated['slug_translated'], $translated['slug'][$locale]);
});

 
it('can provide models with tag name and slug translated for alternate locales',function()
{
    $this->testModel->attachTag('My Tag');

    $locale = 'fr';

    $tag = $this->testModel->tags->first();
    $tag->setTranslation('name', $locale, 'Mon tag');
    $tag->save();

    $translated = $this->testModel->tagsTranslated($locale)->first()->toArray();

    $this->assertEquals($translated['name_translated'], $translated['name'][$locale]);
    $this->assertEquals($translated['slug_translated'], $translated['slug'][$locale]);
});