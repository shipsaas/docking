<?php

namespace Tests\Feature;

use App\Events\DocumentTemplateCreated;
use App\Events\DocumentTemplateDestroyed;
use App\Events\DocumentTemplateUpdated;
use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class DocumentTemplateControllerTest extends TestCase
{
    public function testIndexReturnsAListOfTemplates()
    {
        $templates = DocumentTemplate::factory()->count(2)
            ->create();

        $uuids = $templates->pluck('uuid');

        $response = $this->json('GET', 'api/v1/document-templates')
            ->assertOk();

        $this->assertEquals(
            $uuids->toArray(),
            $response->collect('data')->pluck('uuid')->toArray()
        );
    }

    public function testShowReturnsASingleTemplate()
    {
        $template = DocumentTemplate::factory()->create();

        $this->json('GET', 'api/v1/document-templates/' . $template->uuid)
            ->assertOk()
            ->assertJsonFragment([
                'uuid' => $template->uuid,
                'key' => $template->key,
                'category' => $template->category,
                'title' => $template->title,
                'template' => $template->template,
            ]);
    }

    public function testStoreCreateNewTemplate()
    {
        Event::fake([
            DocumentTemplateCreated::class,
        ]);

        $this->json('POST', 'api/v1/document-templates', [
            'key' => 'seth-phat',
            'title' => 'Seth Phat Template',
            'category' => 'Ok',

            // this won't be included
            'template' => 'aaaa',
        ])->assertCreated();

        $this->assertDatabaseHas('document_templates', [
            'key' => 'seth-phat',
            'title' => 'Seth Phat Template',
            'category' => 'Ok',
            'template' => null,
        ]);

        Event::assertDispatched(DocumentTemplateCreated::class);
    }

    public function testUpdateUpdatesASingleTemplate()
    {
        Event::fake([
            DocumentTemplateUpdated::class,
        ]);

        $template = DocumentTemplate::factory()->create();

        $this->json('PUT', 'api/v1/document-templates/' . $template->uuid, [
            'category' => 'Hihi',
            'title' => 'Funny template',
            'template' => '<h1>Hello {{ $name }}</h1>',
            'default_variables' => [
                'name' => 'DocKing',
            ],
            'metadata' => [
                'driver' => 'gotenberg',
            ],
        ])
            ->assertOk()
            ->assertJsonFragment([
                'uuid' => $template->uuid,
            ]);

        $this->assertDatabaseHas('document_templates', [
            'uuid' => $template->uuid,
            'category' => 'Hihi',
            'title' => 'Funny template',
            'template' => '<h1>Hello {{ $name }}</h1>',
            'default_variables->name' => 'DocKing',
            'metadata->driver' => 'gotenberg',
        ]);

        Event::assertDispatched(
            DocumentTemplateUpdated::class,
            fn (DocumentTemplateUpdated $event) => $event->template->is($template)
        );
    }

    public function testDestroySoftDeletesTheTemplate()
    {
        Event::fake([
            DocumentTemplateDestroyed::class,
        ]);

        $template = DocumentTemplate::factory()->create();

        $this->json('DELETE', 'api/v1/document-templates/' . $template->uuid)
            ->assertOk()
            ->assertJsonFragment([
                'uuid' => $template->uuid,
            ]);

        $this->assertSoftDeleted('document_templates', [
            'uuid' => $template->uuid,
        ]);

        Event::assertDispatched(
            DocumentTemplateDestroyed::class,
            fn (DocumentTemplateDestroyed $event) => $event->template->is($template)
        );
    }

    public function testPreviewHtmlReturnsRenderedHtmlString()
    {
        $template = DocumentTemplate::factory()->create([
            'template' => 'Hello {{ $name }}',
            'default_variables' => [
                'name' => 'Seth',
            ],
        ]);

        $this->json('POST', 'api/v1/document-templates/' . $template->uuid . '/preview-html')
            ->assertOk()
            ->assertJsonFragment([
                'html' => 'Hello Seth',
            ]);
    }

    public function testDuplicateTemplateReturnsErrorOnDuplicatedKey()
    {
        $template = DocumentTemplate::factory()->create();

        $this->json('POST', 'api/v1/document-templates/' . $template->uuid . '/duplicate', [
            'key' => $template->key,
        ])->assertJsonValidationErrorFor('key');
    }

    public function testDuplicateTemplateReturnsOk()
    {
        Event::fake([
            DocumentTemplateCreated::class,
        ]);

        $template = DocumentTemplate::factory()->create();

        $this->json('POST', 'api/v1/document-templates/' . $template->uuid . '/duplicate', [
            'key' => 'my-new-awesome-template',
        ])->assertCreated();

        $this->assertDatabaseHas('document_templates', [
            'key' => 'my-new-awesome-template',
            'title' => $template->title . ' (Duplicated)',
        ]);

        $newTemplate = DocumentTemplate::firstWhere('key', 'my-new-awesome-template');

        Event::assertDispatched(
            DocumentTemplateCreated::class,
            fn (DocumentTemplateCreated $event) => !$event->template->is($template)
                && $event->template->is($newTemplate)
        );
    }
}
