<?php

namespace App\Services\TemplatingServices;

use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Blade;

class BladeTemplatingService implements TemplatingServiceContract
{
    public function renderHtml(DocumentTemplate $template, array $variables): string
    {
        return Blade::render($template->template, $variables, deleteCachedView: true);
    }
}
