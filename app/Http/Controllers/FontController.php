<?php

namespace App\Http\Controllers;

use App\Models\Font;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FontController extends Controller
{
    public function index()
    {

    }

    public function show()
    {

    }

    public function store()
    {

    }

    public function update()
    {

    }

    public function destroy(Font $font)
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
