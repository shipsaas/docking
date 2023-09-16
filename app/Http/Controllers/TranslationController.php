<?php

namespace App\Http\Controllers;

use App\Http\Requests\TranslationIndexRequest;
use App\Http\Requests\TranslationStoreRequest;
use App\Http\Requests\TranslationUpdateRequest;
use App\Http\Resources\TranslationResource;
use App\Models\Translation;
use Illuminate\Http\JsonResponse;

class TranslationController extends Controller
{
    public function index(TranslationIndexRequest $request): JsonResponse
    {
        $translations = $request->buildQueryBuilder()
            ->paginate();

        return TranslationResource::collection($translations)->response();
    }

    public function show(Translation $translation): JsonResponse
    {
        return TranslationResource::make($translation)->response();
    }

    public function store(TranslationStoreRequest $request): JsonResponse
    {
        $translation = Translation::create($request->validated());

        return new JsonResponse([
            'uuid' => $translation->uuid,
            'created' => $translation->wasRecentlyCreated,
        ]);
    }

    public function update(TranslationUpdateRequest $request, Translation $translation): JsonResponse
    {
        $updateStatus = $translation->update($request->validated());

        return new JsonResponse([
            'uuid' => $translation->uuid,
            'updated' => $updateStatus,
        ]);
    }

    public function destroy(Translation $translation): JsonResponse
    {
        $deleteResult = $translation->delete();

        return new JsonResponse([
            'uuid' => $translation->uuid,
            'deleted' => $deleteResult,
        ]);
    }
}
