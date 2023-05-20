<?php

namespace App\Services\TemplatingServices;

use App\Models\DocumentTemplate;

interface TemplatingServiceContract
{
    /**
     * The contractor must be able to render the HTML with given template & variables
     */
    public function renderHtml(DocumentTemplate $template, array $variables): string;
}
