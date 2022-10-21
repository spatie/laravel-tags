<?php

use Spatie\Tags\Test\TestClasses\TestCustomTagModel;

beforeEach(function () {
    $this->assertCount(0, TestCustomTagModel::all());
});


it('can translate other attributes',function()
{
    $tag = TestCustomTagModel::findOrCreateFromString('string');
    $locale = 'es';

    $tag->setTranslation('description', $locale, 'Esto es un tag');
    $tag->save();

    $translated = TestCustomTagModel::where('description', 'LIKE', '%' . $locale . '%')->first()->toArray();

    $this->assertEquals('Esto es un tag', $translated['description'][$locale]);
    $this->assertCount(1, TestCustomTagModel::all());
});
