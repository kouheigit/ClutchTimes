<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\Invitation\RegisterController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\FreedayController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ServiceOptionController;
use App\Http\Controllers\CalendarOptionController;
use App\Http\Controllers\AddOrderController;
use App\Http\Controllers\AddOrderDetailController;
use App\Http\Controllers\UserPointController;
use App\Http\Controllers\UserPointLogController;
use App\Http\Controllers\ReservationLogController;
use App\Http\Controllers\ReleaseLogController;
use App\Http\Controllers\MailTemplateController;
use App\Http\Controllers\VeritransLogController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\CartDetailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;

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

Route::get('/', function () {
    return view('welcome');
});

// DashboardControllerに移行

// トップページ（認証必須）
Route::get('/top', [TopController::class, 'index'])
    ->middleware(['auth'])
    ->name('top');

// 予約関連（認証必須）
Route::middleware(['auth'])->group(function () {
    Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');
    Route::get('/reservation/create', [ReservationController::class, 'create'])->name('reservation.create');
    Route::get('/reservation/service', [ReservationController::class, 'service'])->name('reservation.service');
    Route::post('/reservation/cart/add', [ReservationController::class, 'cart_add'])->name('reservation.cart_add');
    Route::get('/reservation/cart', [ReservationController::class, 'cart'])->name('reservation.cart');
    Route::put('/reservation/cart/{tmp_order_detail}', [ReservationController::class, 'cart_update'])->name('reservation.cart_update');
    Route::delete('/reservation/cart/{tmp_order_detail}', [ReservationController::class, 'cart_delete'])->name('reservation.cart_delete');
    Route::get('/reservation/confirm', [ReservationController::class, 'confirm'])->name('reservation.confirm');
    Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/reservation/{reservation}', [ReservationController::class, 'show'])->name('reservation.show');
    Route::get('/reservation/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservation.edit');
    Route::put('/reservation/{reservation}', [ReservationController::class, 'update'])->name('reservation.update');
    Route::get('/reservation/complete', [ReservationController::class, 'complete'])->name('reservation.complete');
    Route::post('/reservation/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel');
    
    // カレンダー関連
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/{year}/{month}/{day}', [CalendarController::class, 'detail'])->name('calendar.detail');
    
    // マイページ関連
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/edit', [MypageController::class, 'edit'])->name('mypage.edit');
    Route::put('/mypage', [MypageController::class, 'update'])->name('mypage.update');
    Route::get('/mypage/history', [MypageController::class, 'history'])->name('mypage.history');
    Route::get('/mypage/pointlog', [MypageController::class, 'pointlog'])->name('mypage.pointlog');
    Route::get('/mypage/orders', [MypageController::class, 'orders'])->name('mypage.orders');
    Route::get('/mypage/orders/{order}', [MypageController::class, 'orderShow'])->name('mypage.order.show');
    Route::get('/mypage/reservations', [MypageController::class, 'reservations'])->name('mypage.reservations');
    
    // サービス注文関連
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    
    // カート関連（現地注文用）
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::put('/cart/{cart_detail}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart_detail}', [CartController::class, 'delete'])->name('cart.delete');
    Route::get('/cart/{cart}/confirm', [CartController::class, 'confirm'])->name('cart.confirm');
    Route::post('/cart/{cart}', [CartController::class, 'store'])->name('cart.store');
    Route::get('/cart/complete', [CartController::class, 'complete'])->name('cart.complete');
    
    // 招待関連（オーナーのみ）
    Route::get('/invitation', [InvitationController::class, 'index'])->name('invitation.index');
    Route::get('/invitation/create', [InvitationController::class, 'create'])->name('invitation.create');
    Route::post('/invitation', [InvitationController::class, 'store'])->name('invitation.store');
    Route::delete('/invitation/{invitation}', [InvitationController::class, 'destroy'])->name('invitation.destroy');
    Route::get('/invitation/complete', function () {
        return view('invitation.complete');
    })->name('invitation.complete');
    
    // お知らせ・情報関連
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/information', [InformationController::class, 'index'])->name('information.index');
    Route::get('/information/{information}', [InformationController::class, 'show'])->name('information.show');
    
    // ホテル関連
    Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
    Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');
    
    // 注文関連
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/order-details', [OrderDetailController::class, 'index'])->name('order-details.index');
    Route::get('/order-details/{orderDetail}', [OrderDetailController::class, 'show'])->name('order-details.show');
    
    // ポイント関連
    Route::get('/points', [PointController::class, 'index'])->name('points.index');
    Route::get('/points/history', [PointController::class, 'history'])->name('points.history');
    Route::get('/user-points', [UserPointController::class, 'index'])->name('user-points.index');
    Route::get('/user-points/{userPoint}', [UserPointController::class, 'show'])->name('user-points.show');
    Route::get('/user-point-logs', [UserPointLogController::class, 'index'])->name('user-point-logs.index');
    Route::get('/user-point-logs/{userPointLog}', [UserPointLogController::class, 'show'])->name('user-point-logs.show');
    
    // FREEDAY関連
    Route::get('/freedays', [FreedayController::class, 'index'])->name('freedays.index');
    Route::get('/freedays/{freeday}', [FreedayController::class, 'show'])->name('freedays.show');
    
    // 休日関連
    Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index');
    Route::get('/holidays/{holiday}', [HolidayController::class, 'show'])->name('holidays.show');
    
    // サービスオプション関連
    Route::get('/service-options', [ServiceOptionController::class, 'index'])->name('service-options.index');
    Route::get('/service-options/{serviceOption}', [ServiceOptionController::class, 'show'])->name('service-options.show');
    
    // カレンダーオプション関連
    Route::get('/calendar-options', [CalendarOptionController::class, 'index'])->name('calendar-options.index');
    Route::get('/calendar-options/{calendarOption}', [CalendarOptionController::class, 'show'])->name('calendar-options.show');
    
    // 追加注文関連
    Route::get('/add-orders', [AddOrderController::class, 'index'])->name('add-orders.index');
    Route::get('/add-orders/{addOrder}', [AddOrderController::class, 'show'])->name('add-orders.show');
    Route::get('/add-order-details', [AddOrderDetailController::class, 'index'])->name('add-order-details.index');
    Route::get('/add-order-details/{addOrderDetail}', [AddOrderDetailController::class, 'show'])->name('add-order-details.show');
    
    // ログ関連
    Route::get('/reservation-logs', [ReservationLogController::class, 'index'])->name('reservation-logs.index');
    Route::get('/reservation-logs/{reservationLog}', [ReservationLogController::class, 'show'])->name('reservation-logs.show');
    Route::get('/release-logs', [ReleaseLogController::class, 'index'])->name('release-logs.index');
    Route::get('/release-logs/{releaseLog}', [ReleaseLogController::class, 'show'])->name('release-logs.show');
    Route::get('/veritrans-logs', [VeritransLogController::class, 'index'])->name('veritrans-logs.index');
    Route::get('/veritrans-logs/{veritransLog}', [VeritransLogController::class, 'show'])->name('veritrans-logs.show');
    
    // メールテンプレート関連
    Route::get('/mail-templates', [MailTemplateController::class, 'index'])->name('mail-templates.index');
    Route::get('/mail-templates/{mailTemplate}', [MailTemplateController::class, 'show'])->name('mail-templates.show');
    
    // カート明細関連
    Route::get('/cart-details', [CartDetailController::class, 'index'])->name('cart-details.index');
    Route::get('/cart-details/{cartDetail}', [CartDetailController::class, 'show'])->name('cart-details.show');
    
    // ダッシュボード
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // 検索
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    
    // レポート
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/reservations', [ReportController::class, 'reservations'])->name('reports.reservations');
    Route::get('/reports/orders', [ReportController::class, 'orders'])->name('reports.orders');
    
    // 設定
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/settings/profile', [SettingController::class, 'profile'])->name('settings.profile');
    Route::put('/settings/profile', [SettingController::class, 'updateProfile'])->name('settings.profile.update');
    Route::get('/settings/password', [SettingController::class, 'password'])->name('settings.password');
    Route::put('/settings/password', [SettingController::class, 'updatePassword'])->name('settings.password.update');
    
    // 決済関連
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{veritransLog}', [PaymentController::class, 'show'])->name('payments.show');
    
    // 通知関連
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    
    // プロフィール関連
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// 招待登録（認証不要）
Route::get('/invitation/register/{token}', [RegisterController::class, 'show'])->name('invitation.register');
Route::post('/invitation/register', [RegisterController::class, 'store'])->name('invitation.register.store');

require __DIR__.'/auth.php';
