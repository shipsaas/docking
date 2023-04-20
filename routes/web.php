<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes of DocKing
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});
