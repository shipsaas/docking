<?php

namespace App\Http\Controllers;

use App\Events\DocumentTemplateCreated;
use App\Events\DocumentTemplateDestroyed;
use App\Events\DocumentTemplateUpdated;
use App\Http\Requests\DocumentTemplateDuplicateRequest;
use App\Http\Requests\DocumentTemplateIndexRequest;
use App\Http\Requests\DocumentTemplateStoreRequest;
use App\Http\Requests\DocumentTemplateUpdateRequest;
use App\Http\Resources\DocumentTemplateResource;
use App\Models\DocumentTemplate;
use App\Services\TemplatingRenderManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use stdClass;

class DocumentTemplateController extends Controller
{
    public function index(DocumentTemplateIndexRequest $request): JsonResponse
    {
        $records = $request->buildQueryBuilder()
            ->paginate($request->getLimit());

        return DocumentTemplateResource::collection($records)
            ->response($request);
    }

    public function show(DocumentTemplate $documentTemplate): JsonResponse
    {
        return DocumentTemplateResource::make($documentTemplate)->response();
    }

    public function store(DocumentTemplateStoreRequest $request): JsonResponse
    {
        $documentTemplate = DocumentTemplate::create([
            ...$request->validated(),
            'default_variables' => [
                'your-variable' => 'here',
            ],
            'metadata' => [
                'driver' => 'gotenberg|wkhtmltopdf',
            ],
        ]);

        $documentTemplate && Event::dispatch(new DocumentTemplateCreated($documentTemplate));

        return new JsonResponse([
            'uuid' => $documentTemplate->uuid,
            'created' => $documentTemplate->wasRecentlyCreated,
        ], 201);
    }

    public function update(
        DocumentTemplateUpdateRequest $request,
        DocumentTemplate $documentTemplate
    ): JsonResponse {
        $updateResult = $documentTemplate->update([
            ...$request->validated(),
            'default_variables' => $request->input('default_variables')
                ?: $documentTemplate->default_variables
                    ?: new stdClass(),
            'metadata' => $request->input('metadata')
                ?: $documentTemplate->metadata
                    ?: new stdClass(),
        ]);

        $updateResult && Event::dispatch(new DocumentTemplateUpdated($documentTemplate));

        return new JsonResponse([
            'uuid' => $documentTemplate->uuid,
            'updated' => $updateResult,
        ]);
    }

    public function destroy(DocumentTemplate $documentTemplate): JsonResponse
    {
        $deleteResult = (bool) $documentTemplate->delete();

        $deleteResult && Event::dispatch(new DocumentTemplateDestroyed($documentTemplate));

        return new JsonResponse([
            'uuid' => $documentTemplate->uuid,
            'deleted' => $deleteResult,
        ]);
    }

    public function previewHtml(
        DocumentTemplate $documentTemplate,
        TemplatingRenderManager $manager
    ): JsonResponse {
        $html = $manager->setMode($documentTemplate->getTemplatingMode())
            ->renderHtml($documentTemplate, $documentTemplate->default_variables);

        return new JsonResponse([
            'html' => $html,
        ]);
    }

    public function duplicate(
        DocumentTemplateDuplicateRequest $request,
        DocumentTemplate $documentTemplate
    ): JsonResponse {
        $newTemplate = $documentTemplate->replicate([
            'uuid',
            'key',
        ])->fill([
            'key' => $request->validated('key'),
            'title' => $documentTemplate->title . ' (Duplicated)',
        ]);

        $newTemplate->save();

        Event::dispatch(new DocumentTemplateCreated($documentTemplate));

        return new JsonResponse([
            'uuid' => $newTemplate->uuid,
        ], 201);
    }
}
