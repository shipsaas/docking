<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentFileController;
use App\Http\Controllers\DocumentTemplateController;
use App\Http\Controllers\FontController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PdfRenderController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\TranslationGroupController;
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
            ->parameter('document-templates', 'documentTemplateUuidKey')
            ->except([
                'create',
                'edit',
            ]);
        Route::post(
            'document-templates/{documentTemplateUuidKey}/preview-html',
            [DocumentTemplateController::class, 'previewHtml']
        );
        Route::post(
            'document-templates/{documentTemplateUuidKey}/duplicate',
            [DocumentTemplateController::class, 'duplicate']
        );

        Route::post(
            'document-templates/{documentTemplateUuidKey}/pdfs',
            [PdfRenderController::class, 'render']
        );
        Route::post(
            'document-templates/{documentTemplateUuidKey}/pdfs-async',
            [PdfRenderController::class, 'renderAsync']
        );

        Route::get('document-files', [DocumentFileController::class, 'index']);
        Route::get('document-files/{documentFile}', [DocumentFileController::class, 'show']);

        // v1.2.0 Fonts
        Route::resource('fonts', FontController::class)
            ->only(['index', 'store', 'destroy']);

        // v1.4.0 Languages, TranslationGroups, Translations
        Route::resource('languages', LanguageController::class)
            ->except([
                'create',
                'edit',
            ]);
        Route::resource('translation-groups', TranslationGroupController::class)
            ->parameter('translation-groups', 'translationGroup')
            ->except([
                'create',
                'edit',
            ]);
        Route::resource('translations', TranslationController::class)
            ->except([
                'create',
                'edit',
            ]);
    });
