<?php

use Illuminate\Routing\Router;
use Encore\Admin\Facades\Admin;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    // ユーザー管理
    $router->resource('users', UserController::class);
    
    // ホテル管理
    $router->resource('hotels', HotelController::class);
    
    // サービス管理
    $router->resource('services', ServiceController::class);
    $router->resource('service_options', ServiceOptionController::class);
    
    // カレンダー管理
    $router->resource('calendars', CalendarController::class);
    $router->resource('calendar_options', CalendarOptionController::class);
    $router->resource('freedays', FreedayController::class);
    
    // 予約管理
    $router->resource('reservations', ReservationController::class);
    $router->resource('orders', OrderController::class);
    $router->resource('order_details', OrderDetailController::class);
    
    // 追加注文管理
    $router->resource('add_orders', AddOrderController::class);
    $router->resource('add_order_details', AddOrderDetailController::class);
    
    // カート管理
    $router->resource('carts', CartController::class);
    $router->resource('cart_details', CartDetailController::class);
    
    // ポイント管理
    $router->resource('user_points', UserPointController::class);
    $router->resource('user_point_logs', UserPointLogController::class);
    
    // 招待管理
    $router->resource('invitations', InvitationController::class);
    
    // 休日管理
    $router->resource('holidays', HolidayController::class);
    
    // お知らせ管理
    $router->resource('news', NewsController::class);
    $router->resource('information', InformationController::class);
    
    // ログ管理
    $router->resource('veritrans_logs', VeritransLogController::class);
    $router->resource('reservation_logs', ReservationLogController::class);
    $router->resource('release_logs', ReleaseLogController::class);
    
    // メールテンプレート管理
    $router->resource('mail_templates', MailTemplateController::class);
    
    // APIルート（Ajax検索用）
    $router->group(['prefix' => 'api'], function (Router $router) {
        $router->get('users', 'ApiController@users');
        $router->get('reservations', 'ApiController@reservations');
        $router->get('calendars', 'ApiController@calendars');
        $router->get('carts', 'ApiController@carts');
        $router->get('services', 'ApiController@services');
        $router->get('service_options', 'ApiController@serviceOptions');
        $router->get('add_orders', 'ApiController@addOrders');
    });
});

