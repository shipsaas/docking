<?php

namespace App\Services\TemplatingServices;

use App\Models\DocumentTemplate;
use App\Utils\TemplatingErrorMessage;
use Illuminate\Support\Facades\Blade;

class BladeTemplatingService implements TemplatingServiceContract
{
    public function renderHtml(DocumentTemplate $template, array $variables): string
    {
        return rescue(
            fn () => Blade::render(
                $template->template,
                $variables,
                deleteCachedView: true
            ),
            TemplatingErrorMessage::resolveThrowable(...)
        );
    }
}
