<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes of DocKing
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    \Illuminate\Support\Facades\Log::error('test');
    return view('welcome');
});

if (config('docking.console-enabled')) {
    Route::get('/console', function () {
        return view('console');
    });
}

Route::get('/healthz', function () {
    return 'OK';
});
