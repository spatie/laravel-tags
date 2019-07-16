<?php

namespace Spatie\Translatable\Test;

use Spatie\Tags\Test\TestCase;
use Spatie\Tags\Test\TestModel;

class HasTagsTranslatedTest extends TestCase
{
    /** @var \Spatie\Tags\Test\TestModel */
    protected $testModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->testModel = TestModel::create(['name' => 'default']);
    }

    /** @test */
    public function it_provides_models_with_tag_name_and_slug_already_translated()
    {
        $this->testModel->attachTag('My Tag');

        $locale = app()->getLocale();
        $translated = $this->testModel->tagsTranslated->first()->toArray();

        $this->assertEquals($translated['name_translated'], $translated['name'][$locale]);
        $this->assertEquals($translated['slug_translated'], $translated['slug'][$locale]);
    }

    /** @test */
    public function it_can_provide_models_with_tag_name_and_slug_translated_for_alternate_locales()
    {
        $this->testModel->attachTag('My Tag');

        $locale = 'fr';

        $tag = $this->testModel->tags->first();
        $tag->setTranslation('name', $locale, 'Mon tag');
        $tag->save();

        $translated = $this->testModel->tagsTranslated($locale)->first()->toArray();

        $this->assertEquals($translated['name_translated'], $translated['name'][$locale]);
        $this->assertEquals($translated['slug_translated'], $translated['slug'][$locale]);
    }
}
