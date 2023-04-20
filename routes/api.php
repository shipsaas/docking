<?php

use App\Http\Controllers\DocumentTemplateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes of DocKingx
|--------------------------------------------------------------------------
*/

Route::prefix('v1')
    ->group(function () {
        Route::resource('document-templates', DocumentTemplateController::class);
    });
