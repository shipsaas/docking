<?php

namespace App\Http\Controllers;

use App\Http\Requests\LanguageIndexRequest;
use App\Http\Requests\LanguageStoreRequest;
use App\Http\Requests\LanguageUpdateRequest;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use Illuminate\Http\JsonResponse;

class LanguageController extends Controller
{
    public function index(LanguageIndexRequest $request): JsonResponse
    {
        $fonts = $request->buildQueryBuilder()
            ->get();

        return LanguageResource::collection($fonts)->response();
    }

    public function show(Language $language): JsonResponse
    {
        return LanguageResource::make($language)->response();
    }

    public function store(LanguageStoreRequest $request): JsonResponse
    {
        $language = Language::create($request->validated());

        return new JsonResponse([
            'uuid' => $language->uuid,
            'created' => $language->wasRecentlyCreated,
        ]);
    }

    public function update(LanguageUpdateRequest $request, Language $language): JsonResponse
    {
        $updateStatus = $language->update($request->validated());

        return new JsonResponse([
            'uuid' => $language->uuid,
            'updated' => $updateStatus,
        ]);
    }

    public function destroy(Language $language): JsonResponse
    {
        $deleteResult = $language->delete();

        return new JsonResponse([
            'uuid' => $language->uuid,
            'deleted' => $deleteResult,
        ]);
    }
}
