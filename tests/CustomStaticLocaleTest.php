<?php

use Spatie\Tags\Test\TestClasses\TestCustomTagStaticLocaleModel;

beforeEach(function () {
    expect(TestCustomTagStaticLocaleModel::all())->toHaveCount(0);
});

it('can_use_static_locale', function () {
    app()->setLocale('es');

    $tag = TestCustomTagStaticLocaleModel::findOrCreateFromString('string');

    $staticLocale = 'en';

    $translated = TestCustomTagStaticLocaleModel::where('name', 'LIKE', '%' . $staticLocale . '%')->first()->toArray();

    expect($translated['name'][$staticLocale])->toBe('string');
    expect(TestCustomTagStaticLocaleModel::all())->toHaveCount(1);
});
