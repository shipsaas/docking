<?php

namespace App\Events;

use App\Models\DocumentTemplate;

class DocumentTemplateDestroyed
{
    public function __construct(public DocumentTemplate $template)
    {
    }
}
