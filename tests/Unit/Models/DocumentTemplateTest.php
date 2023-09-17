<?php

namespace Tests\Unit\Models;

use App\Enums\TemplatingMode;
use App\Models\DocumentFile;
use App\Models\DocumentTemplate;
use App\Models\DocumentTemplateTranslationGroup;
use App\Models\TranslationGroup;
use Tests\TestCase;

class DocumentTemplateTest extends TestCase
{
    public function testHasManyDocumentFiles()
    {
        /**
         * @var DocumentTemplate $template
         */
        $template = DocumentTemplate::factory()->create();
        $file = DocumentFile::factory()->create([
            'document_template_uuid' => $template->uuid,
        ]);

        $relatedFiles = $template->documentFiles()->get();

        $this->assertNotNull(
            $relatedFiles->find($file->uuid)
        );
    }

    public function testGetTemplatingModeReturnsTheModeFromMetadata()
    {
        $template = new DocumentTemplate([
            'metadata' => [
                'templating' => TemplatingMode::MARKDOWN->value,
            ],
        ]);

        $this->assertSame(TemplatingMode::MARKDOWN, $template->getTemplatingMode());
    }

    public function testGetTemplatingModeReturnsFallbackMode()
    {
        $template = new DocumentTemplate();

        $this->assertSame(TemplatingMode::BLADE, $template->getTemplatingMode());
    }

    public function testBelongsToManyTranslationGroups()
    {
        /**
         * @var DocumentTemplate $template
         */
        $template = DocumentTemplate::factory()->create();
        $translationGroup = TranslationGroup::factory()->create();

        DocumentTemplateTranslationGroup::create([
            'document_template_id' => $template->uuid,
            'translation_group_id' => $translationGroup->uuid,
        ]);

        $foundTranslationGroups = $template->translationGroups()->get();

        $this->assertNotNull(
            $foundTranslationGroups->find($translationGroup->uuid)
        );
    }
}
