<?php

namespace App\Http\Controllers;

use App\Http\Requests\TranslationIndexRequest;
use App\Http\Requests\TranslationStoreRequest;
use App\Http\Requests\TranslationUpdateRequest;
use App\Http\Resources\TranslationResource;
use App\Models\Translation;
use App\Models\TranslationGroup;
use Illuminate\Http\JsonResponse;

class TranslationController extends Controller
{
    public function index(TranslationIndexRequest $request): JsonResponse
    {
        $translations = $request->buildQueryBuilder()
            ->with(['translationGroup'])
            ->paginate();

        return TranslationResource::collection($translations)->response();
    }

    public function show(Translation $translation): JsonResponse
    {
        return TranslationResource::make($translation)->response();
    }

    public function store(TranslationStoreRequest $request): JsonResponse
    {
        $translationGroup = TranslationGroup::find($request->validated('translation_group_id'));
        $translation = Translation::create([
            ...$request->validated(),
            'key' => "{$translationGroup->key}.{$request->input('key')}",
        ]);

        return new JsonResponse([
            'uuid' => $translation->uuid,
            'created' => $translation->wasRecentlyCreated,
        ]);
    }

    public function update(TranslationUpdateRequest $request, Translation $translation): JsonResponse
    {
        $translationGroup = TranslationGroup::find($request->validated('translation_group_id'));

        $updateStatus = $translation->update([
            ...$request->validated(),
            'key' => "{$translationGroup->key}.{$request->input('key')}",
        ]);

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
