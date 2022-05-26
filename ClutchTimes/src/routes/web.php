<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//ルートをログイン画面に設定する

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*
Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/tos', [App\Http\Controllers\tosController::class, 'index'])->name('tos');
Route::post('/tos', [App\Http\Controllers\tosController::class, 'tospost'])->name('tos');
//Auth::routes();
Auth::routes(['verify' => true]);
//Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('verified');
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//admin
Route::group(['prefix' => 'admin'], function() {
    Route::get('/',         function () { return redirect('/admin/home'); });
    Route::get('login',     [App\Http\Controllers\Admin\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login',     [App\Http\Controllers\Admin\LoginController::class,'login']);
});

//admin
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function() {
    Route::post('logout',   [App\Http\Controllers\Admin\LoginController::class,'logout'])->name('admin.logout');
    Route::get('home',      [App\Http\Controllers\Admin\HomeController::class,'index'])->name('admin.home');
});
