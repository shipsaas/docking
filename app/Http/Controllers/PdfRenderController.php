<?php

namespace App\Http\Controllers;

use App\Http\Requests\PdfRenderAsyncRequest;
use App\Http\Requests\PdfRenderRequest;
use App\Models\DocumentTemplate;

class PdfRenderController extends Controller
{
    public function render(PdfRenderRequest $request, DocumentTemplate $documentTemplate)
    {

    }

    public function renderAsync(PdfRenderAsyncRequest $request, DocumentTemplate $documentTemplate)
    {

    }
}
