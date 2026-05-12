<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPA Routes
|--------------------------------------------------------------------------
|
| React SPA用のルーティング
| すべてのリクエストをspa.blade.phpにルーティング
|
*/

Route::get('/{any}', function () {
    return view('spa');
})->where('any', '.*');

