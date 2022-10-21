<?php

use Spatie\Tags\Test\TestClasses\TestCustomTagStaticLocaleModel;

beforeEach(function () {
    $this->assertCount(0, TestCustomTagStaticLocaleModel::all());
});

it('can_use_static_locale', function()
{
    app()->setLocale('es');

    $tag = TestCustomTagStaticLocaleModel::findOrCreateFromString('string');

    $staticLocale = 'en';

    $translated = TestCustomTagStaticLocaleModel::where('name', 'LIKE', '%' . $staticLocale . '%')->first()->toArray();

    $this->assertEquals('string', $translated['name'][$staticLocale]);
    $this->assertCount(1, TestCustomTagStaticLocaleModel::all());
});