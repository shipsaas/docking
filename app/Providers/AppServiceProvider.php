<?php

namespace App\Providers;

use App\Services\PdfRenderManager;
use Illuminate\Support\ServiceProvider;
use Services\PdfRenderers\GotenbergRendererService;
use Services\PdfRenderers\MpdfRendererService;
use Services\PdfRenderers\WkHtmlToPdfRendererService;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //
    }

    public function register(): void
    {
        $this->app->singleton(GotenbergRendererService::class, function () {
            return new GotenbergRendererService(
                $this->app['config']->get('docking.drivers.gotenberg.endpoint')
            );
        });

        $this->app->singleton(MpdfRendererService::class);
        $this->app->singleton(WkHtmlToPdfRendererService::class);

        $this->app->singleton(PdfRenderManager::class);
    }
}
