<?php

namespace App\Http\Controllers;

use App\Http\Requests\FontIndexRequest;
use App\Http\Requests\FontStoreRequest;
use App\Http\Resources\FontResource;
use App\Models\Font;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FontController extends Controller
{
    public function index(FontIndexRequest $request): JsonResponse
    {
        $fonts = $request->buildQueryBuilder()
            ->paginate();

        return FontResource::collection($fonts)->response();
    }

    public function store(FontStoreRequest $request): JsonResponse
    {
        $uploadedFile = $request->file('font');
        $extension = $uploadedFile->clientExtension();

        $serverFileName = Str::uuid()->toString() . "." . $extension;
        $storedFile = $uploadedFile
            ->storeAs('fonts', $serverFileName, [
                'disk' => 'local',
            ]);

        if ($storedFile === false) {
            return new JsonResponse([
                'error' => 'Failed to store font file',
            ], 400);
        }

        $font = Font::create([
            'key' => $request->validated('key'),
            'name' => $request->validated('name'),
            'path' => $storedFile,
        ]);

        return new JsonResponse([
            'uuid' => $font->uuid,
            'deleted' => $font->wasRecentlyCreated,
        ]);
    }

    public function destroy(Font $font): JsonResponse
    {
        $deleteResult = $font->delete();
        if ($deleteResult) {
            Storage::drive('local')->delete($font->path);
        }

        return new JsonResponse([
            'uuid' => $font->uuid,
            'deleted' => $deleteResult,
        ]);
    }
}
