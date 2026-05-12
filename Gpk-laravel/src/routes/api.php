<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReservationApiController;
use App\Http\Controllers\Api\ServiceApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\CalendarApiController;
use App\Http\Controllers\Api\PointApiController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\InformationApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// 認証必須API
Route::middleware('auth:sanctum')->group(function () {
    
    // ユーザー情報
    Route::get('/user', [UserApiController::class, 'show']);
    Route::put('/user', [UserApiController::class, 'update']);
    
    // 予約API
    Route::apiResource('reservations', ReservationApiController::class);
    Route::post('reservations/{reservation}/cancel', [ReservationApiController::class, 'cancel']);
    
    // サービスAPI
    Route::apiResource('services', ServiceApiController::class);
    
    // カートAPI
    Route::get('cart', [CartApiController::class, 'index']);
    Route::post('cart/add', [CartApiController::class, 'add']);
    Route::put('cart/{cartDetail}', [CartApiController::class, 'update']);
    Route::delete('cart/{cartDetail}', [CartApiController::class, 'remove']);
    Route::post('cart/checkout', [CartApiController::class, 'checkout']);
    
    // カレンダーAPI
    Route::get('calendars/available', [CalendarApiController::class, 'available']);
    Route::get('calendars/{year}/{month}', [CalendarApiController::class, 'monthly']);
    
    // ポイントAPI
    Route::get('points', [PointApiController::class, 'balance']);
    Route::get('points/history', [PointApiController::class, 'history']);
});

// 公開API（認証不要）
Route::get('news', [NewsApiController::class, 'index']);
Route::get('news/{id}', [NewsApiController::class, 'show']);
Route::get('information', [InformationApiController::class, 'index']);
Route::get('information/{id}', [InformationApiController::class, 'show']);
