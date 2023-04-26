<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentTemplateIndexRequest;
use App\Http\Requests\DocumentTemplateStoreRequest;
use App\Http\Requests\DocumentTemplateUpdateRequest;
use App\Http\Resources\DocumentTemplateCollection;
use App\Http\Resources\DocumentTemplateResource;
use App\Models\DocumentTemplate;
use Illuminate\Http\JsonResponse;

class DocumentTemplateController extends Controller
{
    public function index(DocumentTemplateIndexRequest $request): JsonResponse
    {
        $records = $request->buildQueryBuilder()
            ->paginate($request->getLimit());

        return DocumentTemplateCollection::collection($records)->response();
    }

    public function show(DocumentTemplate $documentTemplate): JsonResponse
    {
        return DocumentTemplateResource::make($documentTemplate)->response();
    }

    public function store(DocumentTemplateStoreRequest $request): JsonResponse
    {
        $documentTemplate = DocumentTemplate::create($request->validated());

        return new JsonResponse([
            'uuid' => $documentTemplate->uuid,
            'created' => $documentTemplate->wasRecentlyCreated,
        ]);
    }

    public function update(
        DocumentTemplateUpdateRequest $request,
        DocumentTemplate $documentTemplate
    ): JsonResponse {
        $updateResult = $documentTemplate->update([
            ...$request->validated(),
            'default_variables' => $request->input('default_variables') ?: [],
            'metadata' => $request->input('metadata') ?: [],
        ]);

        return new JsonResponse([
            'uuid' => $documentTemplate->uuid,
            'updated' => $updateResult,
        ]);
    }

    public function destroy(DocumentTemplate $documentTemplate): JsonResponse
    {
        return new JsonResponse([
            'uuid' => $documentTemplate->uuid,
            'deleted' => (bool) $documentTemplate->delete(),
        ]);
    }
}
