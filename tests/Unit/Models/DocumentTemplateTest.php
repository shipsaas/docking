<?php

namespace Tests\Unit\Models;

use App\Models\DocumentFile;
use App\Models\DocumentTemplate;
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

    public function testRenderHtmlOk()
    {
        $template = DocumentTemplate::factory()->create([
            'template' => 'Hello {{ $name }}',
            'default_variables' => [
                'name' => 'Seth',
            ],
        ]);

        $renderedHtml = $template->renderHtml();

        $this->assertSame('Hello Seth', $renderedHtml);
    }
}
