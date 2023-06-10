<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentFileController;
use App\Http\Controllers\DocumentTemplateController;
use App\Http\Controllers\FontController;
use App\Http\Controllers\PdfRenderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes of DocKing
|--------------------------------------------------------------------------
*/

Route::prefix('v1')
    ->group(function () {
        Route::get('access', [AuthController::class, 'access']);

        Route::resource('document-templates', DocumentTemplateController::class)
            ->parameter('document-template', 'documentTemplate')
            ->except([
                'create',
                'edit',
            ]);
        Route::post(
            'document-templates/{documentTemplate}/preview-html',
            [DocumentTemplateController::class, 'previewHtml']
        );
        Route::post(
            'document-templates/{documentTemplate}/duplicate',
            [DocumentTemplateController::class, 'duplicate']
        );

        Route::post('document-templates/{documentTemplate}/pdfs', [PdfRenderController::class, 'render']);
        Route::post('document-templates/{documentTemplate}/pdfs-async', [PdfRenderController::class, 'renderAsync']);

        Route::get('document-files', [DocumentFileController::class, 'index']);
        Route::get('document-files/{documentFile}', [DocumentFileController::class, 'show']);

        // v1.2.0 Fonts
        Route::resource('fonts', FontController::class)
            ->only(['index', 'store', 'destroy']);
    });
