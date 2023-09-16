<?php

namespace App\Http\Controllers;

use App\Http\Requests\TranslationGroupIndexRequest;
use App\Http\Requests\TranslationGroupStoreRequest;
use App\Http\Requests\TranslationGroupUpdateRequest;
use App\Http\Resources\TranslationGroupResource;
use App\Models\TranslationGroup;
use Illuminate\Http\JsonResponse;

class TranslationGroupController extends Controller
{
    public function index(TranslationGroupIndexRequest $request): JsonResponse
    {
        $translationGroups = $request->buildQueryBuilder()
            ->paginate();

        return TranslationGroupResource::collection($translationGroups)->response();
    }

    public function show(TranslationGroup $translationGroup): JsonResponse
    {
        return TranslationGroupResource::make($translationGroup)->response();
    }

    public function store(TranslationGroupStoreRequest $request): JsonResponse
    {
        $translationGroup = TranslationGroup::create($request->validated());

        return new JsonResponse([
            'uuid' => $translationGroup->uuid,
            'created' => $translationGroup->wasRecentlyCreated,
        ]);
    }

    public function update(TranslationGroupUpdateRequest $request, TranslationGroup $translationGroup): JsonResponse
    {
        $updateStatus = $translationGroup->update($request->validated());

        return new JsonResponse([
            'uuid' => $translationGroup->uuid,
            'updated' => $updateStatus,
        ]);
    }

    public function destroy(TranslationGroup $translationGroup): JsonResponse
    {
        $deleteResult = $translationGroup->delete();

        return new JsonResponse([
            'uuid' => $translationGroup->uuid,
            'deleted' => $deleteResult,
        ]);
    }
}
