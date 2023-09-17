<?php

namespace Tests\Unit\Services\TemplatingServices;

use App\Models\DocumentTemplate;
use App\Models\Translation;
use App\Models\TranslationGroup;
use App\Services\TemplatingServices\BladeTemplatingService;
use Tests\TestCase;

class BladeTemplatingServiceTest extends TestCase
{
    public function testRenderReturnsTheRenderedHtmlUseEnglishTranslations()
    {
        $translationGroup = TranslationGroup::factory()->create([
            'key' => 'base',
        ]);
        Translation::factory()->create([
            'translation_group_id' => $translationGroup->uuid,
            'key' => 'base.hello',
            'name' => 'Hello',
            'text' => [
                'en' => 'Hello',
                'vi' => 'Chao ban',
            ],
        ]);
        Translation::factory()->create([
            'translation_group_id' => $translationGroup->uuid,
            'key' => 'base.hello.nice',
            'name' => 'Nice',
            'text' => [
                'en' => 'Nice day to you',
                'vi' => 'Chuc 1 ngay tot lanh',
            ],
        ]);

        // English
        $this->app->setLocale('en');

        $template = new DocumentTemplate([
            'template' => "@lang('base.hello') {{ \$partner }}, @lang('base.hello.nice')",
        ]);

        $service = new BladeTemplatingService();
        $renderedHtml = $service->renderHtml($template, [
            'partner' => 'Seth',
        ]);

        $this->assertSame('Hello Seth, Nice day to you', $renderedHtml);
    }

    public function testRenderReturnsTheRenderedHtmlUseVietnameseTranslations()
    {
        $translationGroup = TranslationGroup::factory()->create([
            'key' => 'base',
        ]);
        Translation::factory()->create([
            'translation_group_id' => $translationGroup->uuid,
            'key' => 'base.hello',
            'name' => 'Hello',
            'text' => [
                'en' => 'Hello',
                'vi' => 'Chao ban',
            ],
        ]);
        Translation::factory()->create([
            'translation_group_id' => $translationGroup->uuid,
            'key' => 'base.hello.nice.day',
            'name' => 'Nice',
            'text' => [
                'en' => 'Nice day to you',
                'vi' => 'Chuc 1 ngay tot lanh',
            ],
        ]);

        // English
        $this->app->setLocale('vi');

        $template = new DocumentTemplate([
            'template' => "@lang('base.hello') {{ \$partner }}, @lang('base.hello.nice.day')",
        ]);

        $service = new BladeTemplatingService();
        $renderedHtml = $service->renderHtml($template, [
            'partner' => 'Seth',
        ]);

        $this->assertSame('Chao ban Seth, Chuc 1 ngay tot lanh', $renderedHtml);
    }

    public function testRenderOnErrorReturnsTheErrorString()
    {
        $template = new DocumentTemplate([
            'template' => 'Hello {{ $partner }}',
        ]);

        $service = new BladeTemplatingService();
        $renderedHtml = $service->renderHtml($template, [
            'name' => 'Seth',
        ]);

        $this->assertStringStartsWith('Failed to', $renderedHtml);
    }
}
