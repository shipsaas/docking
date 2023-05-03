<?php

namespace Tests\Unit\Models;

use App\Models\DocumentFile;
use Tests\TestCase;

class DocumentFileTest extends TestCase
{
    public function testBelongsToTemplate()
    {
        $file = DocumentFile::factory()->create();

        $template = $file->documentTemplate;

        $this->assertNotNull($template);
        $this->assertSame($template->uuid, $file->document_template_uuid);
    }
}
