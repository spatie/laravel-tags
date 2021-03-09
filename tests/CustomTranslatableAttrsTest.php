<?php

namespace Spatie\Translatable\Test;

use Spatie\Tags\Tag;
use Spatie\Tags\Test\TestCase;
use Spatie\Tags\Test\TestCustomTagModel;

class CustomTranslatableAttrsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->assertCount(0, TestCustomTagModel::all());
    }

    /** @test */
    public function it_can_translate_other_attributes()
    {
        $tag = TestCustomTagModel::findOrCreateFromString('string');
        $locale = 'es';

        $tag->setTranslation('description', $locale, 'Esto es un tag');
        $tag->save();

        $translated = TestCustomTagModel::where('description', 'LIKE', '%' . $locale . '%')->first()->toArray();

        $this->assertEquals('Esto es un tag', $translated['description'][$locale]);
        $this->assertCount(1, TestCustomTagModel::all());
    }
}
