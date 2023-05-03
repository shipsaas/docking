<?php

namespace Tests\Feature;

use App\Models\DocumentFile;
use Tests\TestCase;

class DocumentFileControllerTest extends TestCase
{
    public function testIndexReturnsAListOfFiles()
    {
        $files = DocumentFile::factory()->count(2)
            ->create();

        $uuids = $files->pluck('uuid');
        $names = $files->pluck('documentTemplate.title');

        $response = $this->json('GET', 'api/v1/document-files')
            ->assertOk();

        $this->assertEquals(
            $uuids->toArray(),
            $response->collect('data')->pluck('uuid')->toArray()
        );
        $this->assertEquals(
            $names->toArray(),
            $response->collect('data')->pluck('template_name')->toArray()
        );
    }

    public function testShowReturnsASingleFile()
    {
        $file = DocumentFile::factory()->create();

        $this->json('GET', 'api/v1/document-files/' . $file->uuid)
            ->assertOk()
            ->assertJsonFragment([
                'uuid' => $file->uuid,
                'url' => $file->url,
                'size' => $file->size,
                'path' => $file->path,
            ]);
    }
}
