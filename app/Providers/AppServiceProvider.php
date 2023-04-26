<?php

namespace App\Providers;

use App\Services\PdfRenderers\PdfRendererContract;
use App\Services\PdfRenderManager;
use Illuminate\Support\ServiceProvider;
use App\Services\PdfRenderers\GotenbergRendererService;
use App\Services\PdfRenderers\MpdfRendererService;
use App\Services\PdfRenderers\WkHtmlToPdfRendererService;

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

        $this->app->bind(
            PdfRendererContract::class,
            fn () => $this->app->make(PdfRenderManager::class)->getDriver()
        );
    }
}
