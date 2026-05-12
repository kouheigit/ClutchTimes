<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Order;
use App\Models\Calendar;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        // 統計情報を取得
        $stats = [
            'total_users' => User::count(),
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 1)->count(),
            'confirmed_reservations' => Reservation::where('status', 3)->count(),
            'total_orders' => Order::count(),
            'available_calendars' => Calendar::where('status', 1)->count(),
        ];
        
        return $content
            ->title('ダッシュボード')
            ->description('管理画面トップ')
            ->body(view('admin.dashboard', compact('stats')));
    }
}
