<?php

use Spatie\Tags\Test\TestClasses\TestCustomTagModel;

beforeEach(function () {
    expect(TestCustomTagModel::all())->toHaveCount(0);
});


it('can translate other attributes', function () {
    $tag = TestCustomTagModel::findOrCreateFromString('string');
    $locale = 'es';

    $tag->setTranslation('description', $locale, 'Esto es un tag');
    $tag->save();

    $translated = TestCustomTagModel::where('description', 'LIKE', '%' . $locale . '%')->first()->toArray();

    expect($translated['description'][$locale])->toBe('Esto es un tag');
    expect(TestCustomTagModel::all())->toHaveCount(1);
});
