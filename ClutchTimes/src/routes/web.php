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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('login');

/*
Route::get('/', function () {
    return view('welcome');
});*/

//Auth::routes();
Auth::routes(['verify' => true]);
//Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('verified');
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/home/article', [App\Http\Controllers\HomeController::class, 'article'])->name('auth.article');
Route::get('/home/betshow', [App\Http\Controllers\HomeController::class, 'betshow'])->name('auth.betshow');
Route::post('/home/betshow', [App\Http\Controllers\HomeController::class, 'betshowpost']);
Route::get('/home/vote', [App\Http\Controllers\HomeController::class, 'vote'])->name('auth.vote');



//admin
Route::group(['prefix' => 'admin'], function() {
    Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('admin.home');
    //news記事を追加
    Route::get('addnews', [App\Http\Controllers\Admin\HomeController::class, 'addnews'])->name('admin.addnews');
    Route::post('addnews', [App\Http\Controllers\Admin\HomeController::class, 'addnewspost'])->name('admin.addnews');
    //ログイン機能
    Route::get('login',     [App\Http\Controllers\Admin\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login',     [App\Http\Controllers\Admin\LoginController::class,'login']);
    Route::get('deletearticle', [App\Http\Controllers\Admin\HomeController::class, 'deletearticle'])->name('admin.deletearticle');
    Route::post('deletemessage', [App\Http\Controllers\Admin\HomeController::class, 'deletemessage'])->name('admin.deletemessage');
    Route::get('bet', [App\Http\Controllers\Admin\HomeController::class, 'bet'])->name('admin.bet');
    Route::get('betregister', [App\Http\Controllers\Admin\HomeController::class, 'betregister'])->name('admin.betregister');
    //postに変更する↓
    Route::post('betregisterpost', [App\Http\Controllers\Admin\HomeController::class, 'betregisterpost'])->name('admin.betregisterpost');
});

//admin
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function() {
    Route::post('logout',   [App\Http\Controllers\Admin\LoginController::class,'logout'])->name('admin.logout');
    Route::get('home',      [App\Http\Controllers\Admin\HomeController::class,'index'])->name('admin.home');
});
