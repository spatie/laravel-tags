<?php

namespace Spatie\Translatable\Test;

use Spatie\Tags\Test\TestCase;
use Spatie\Tags\Test\TestClasses\TestCustomTagStaticLocaleModel;

class CustomStaticLocaleTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->assertCount(0, TestCustomTagStaticLocaleModel::all());
    }

    /** @test */
    public function it_can_use_static_locale()
    {
        app()->setLocale('es');

        $tag = TestCustomTagStaticLocaleModel::findOrCreateFromString('string');
        
        $staticLocale = 'en';

        $translated = TestCustomTagStaticLocaleModel::where('name', 'LIKE', '%' . $staticLocale . '%')->first()->toArray();

        $this->assertEquals('string', $translated['name'][$staticLocale]);
        $this->assertCount(1, TestCustomTagStaticLocaleModel::all());
    }
}
