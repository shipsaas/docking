<?php

namespace App\Events;

use App\Models\DocumentTemplate;

class DocumentTemplateCreated
{
    public function __construct(public DocumentTemplate $template)
    {
    }
}
