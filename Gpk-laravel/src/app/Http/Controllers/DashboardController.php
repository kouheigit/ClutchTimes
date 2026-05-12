<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Order;
use App\Models\UserPoint;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private $point_service;
    
    public function __construct(PointService $point_service)
    {
        $this->point_service = $point_service;
    }
    
    /**
     * ダッシュボード表示
     */
    public function index()
    {
        $user = Auth::user();
        
        // 今後の予約
        $upcoming_reservations = Reservation::where('owner_id', $user->id)
            ->where('checkin_date', '>=', Carbon::now()->format('Y-m-d'))
            ->whereIn('status', [1, 2, 3])
            ->orderBy('checkin_date', 'asc')
            ->limit(5)
            ->get();
        
        // 最近の注文
        $recent_orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // ポイント残高
        $user_point = $this->point_service->getAvailablePoints($user->id);
        
        return view('dashboard.index', compact('upcoming_reservations', 'recent_orders', 'user_point'));
    }
}

