<?php

namespace App\Providers;

use App\Enums\TemplatingMode;
use App\Services\PdfRenderers\PdfRendererContract;
use App\Services\PdfRenderManager;
use App\Services\TemplatingServices\BladeTemplatingService;
use App\Services\TemplatingServices\MarkdownTemplatingService;
use App\Services\TemplatingServices\TemplatingServiceContract;
use App\Services\Translations\DatabaseLoader;
use Illuminate\Support\ServiceProvider;
use App\Services\PdfRenderers\GotenbergRendererService;
use App\Services\PdfRenderers\MpdfRendererService;
use App\Services\PdfRenderers\WkHtmlToPdfRendererService;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Mpdf\Mpdf;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
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

        $this->app->bind('mpdf-testing', Mpdf::class);

        $this->bindTemplatingServices();
        $this->bindLocalizationService();
    }

    private function bindTemplatingServices(): void
    {
        $this->app->singleton(BladeTemplatingService::class);
        $this->app->singleton(MarkdownTemplatingService::class);
    }

    private function bindLocalizationService(): void
    {
        $this->app->extend(
            'translation.loader',
            fn () => new DatabaseLoader()
        );
    }
}
