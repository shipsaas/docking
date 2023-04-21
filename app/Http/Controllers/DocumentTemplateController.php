<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;

class DocumentTemplateController extends Controller
{
    public function index()
    {

    }

    public function show(DocumentTemplate $documentTemplate)
    {

    }

    public function store()
    {

    }

    public function update(DocumentTemplate $documentTemplate)
    {

    }

    public function destroy(DocumentTemplate $documentTemplate)
    {
        $documentTemplate->delete();
    }
}
