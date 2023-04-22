<?php

use App\Http\Controllers\DocumentTemplateController;
use App\Http\Controllers\PdfRenderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes of DocKingx
|--------------------------------------------------------------------------
*/

Route::prefix('v1')
    ->group(function () {
        Route::resource('document-templates', DocumentTemplateController::class)
            ->parameter('document-template', 'documentTemplate');

        Route::post('document-templates/{documentTemplate}/pdfs', [PdfRenderController::class, 'render']);
        Route::post('document-templates/{documentTemplate}/pdfs-async', [PdfRenderController::class, 'renderAsync']);
    });
