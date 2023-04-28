<?php

namespace App\Events;

use App\Models\DocumentFile;

class PdfRendered
{
    public function __construct(public DocumentFile $file)
    {
    }
}
