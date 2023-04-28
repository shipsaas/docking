<?php

namespace App\Events;

use App\Models\DocumentTemplate;

class DocumentTemplateUpdated
{
    public function __construct(public DocumentTemplate $template)
    {
    }
}
