<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentFileIndexRequest;
use App\Http\Resources\DocumentFileResource;
use App\Models\DocumentFile;
use Illuminate\Http\JsonResponse;

class DocumentFileController extends Controller
{
    public function index(DocumentFileIndexRequest $request): JsonResponse
    {
        $records = $request->buildQueryBuilder()
            ->paginate($request->getLimit());

        return DocumentFileResource::collection($records)->response();
    }

    public function show(DocumentFile $documentFile): JsonResponse
    {
        return (new DocumentFileResource($documentFile))->response();
    }
}
