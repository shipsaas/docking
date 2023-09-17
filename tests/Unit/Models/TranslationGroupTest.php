<?php

namespace Tests\Unit\Models;

use App\Models\DocumentTemplate;
use App\Models\DocumentTemplateTranslationGroup;
use App\Models\Translation;
use App\Models\TranslationGroup;
use Tests\TestCase;

class TranslationGroupTest extends TestCase
{
    public function testTranslationGroupHasManyTranslations()
    {
        /**
         * @var TranslationGroup $template
         */
        $group = TranslationGroup::factory()->create();
        $translations = Translation::factory()->count(2)
            ->create([
                'translation_group_id' => $group->uuid,
            ]);

        $foundTranslations = $group->translations()->get();

        $this->assertSame(
            $translations->pluck('uuid')->toArray(),
            $foundTranslations->pluck('uuid')->toArray()
        );
    }

    public function testBelongsToManyDocumentTemplates()
    {
        $template = DocumentTemplate::factory()->create();
        $translationGroup = TranslationGroup::factory()->create();

        DocumentTemplateTranslationGroup::create([
            'document_template_id' => $template->uuid,
            'translation_group_id' => $translationGroup->uuid,
        ]);

        $foundTemplates = $translationGroup->documentTemplates()->get();

        $this->assertNotNull(
            $foundTemplates->find($template->uuid)
        );
    }
}
