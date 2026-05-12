<?php
// app/Http/Controllers/TopController.php

namespace App\Http\Controllers;

use App\Services\FreedayService;
use App\Services\WeatherService;
use App\Services\TrafficService;
use App\Models\Calendar;
use App\Models\Reservation;
use App\Models\Information;
use App\Models\UserPoint;
use App\Services\PointService;
use App\Consts\ReservationConst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TopController extends Controller
{
    private $freeday_service;
    private $weather_service;
    private $traffic_service;
    private $point_service;
    
    public function __construct(
        FreedayService $freeday_service,
        WeatherService $weather_service,
        TrafficService $traffic_service,
        PointService $point_service
    ) {
        $this->freeday_service = $freeday_service;
        $this->weather_service = $weather_service;
        $this->traffic_service = $traffic_service;
        $this->point_service = $point_service;
    }
    
    /**
     * トップページ表示
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // 2年分のFIXDAYを取得
        $start_date = Carbon::now()->firstOfYear();
        $end_date = $start_date->copy()->addYears(2)->endOfYear();
        
        $calendars = Calendar::where('user_id', $user->id)
            ->whereBetween('start_date', [$start_date, $end_date])
            ->orderBy('start_date', 'asc')
            ->get();
        
        // FREEDAYSを取得
        $freedays = $this->freeday_service->getFreedays($user);
        
        // 予約を取得（N+1問題対策：リレーションを事前ロード）
        $reservations = Reservation::where('owner_id', $user->id)
            ->with(['hotel', 'user', 'calendar'])
            ->whereIn('status', [
                ReservationConst::STATUS_APPLYING,
                ReservationConst::STATUS_UNDER_RESERVATION,
                ReservationConst::STATUS_RESERVED
            ])
            ->orderBy('checkin_date', 'desc')
            ->limit(5)
            ->get();
        
        // 最新の予約（N+1問題対策：リレーションを事前ロード）
        $last_reservation = Reservation::where('owner_id', $user->id)
            ->with(['hotel', 'user', 'calendar'])
            ->orderBy('checkin_date', 'desc')
            ->first();
        
        // お知らせ取得
        $info = Information::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->first();
        
        // ポイント残高取得
        $user_point = UserPoint::where('user_id', $user->id)->first();
        $pointbalance = $this->point_service->getAvailablePoints($user->id);
        
        // 天気情報取得
        $today_weather = $this->weather_service->getTodayWeather();
        $forecast = $this->weather_service->getForecast();
        
        // 交通情報取得
        $traffic_info = $this->traffic_service->getTrafficInfo();
        
        return view('top.index', compact(
            'calendars',
            'reservations',
            'last_reservation',
            'freedays',
            'info',
            'user_point',
            'pointbalance',
            'today_weather',
            'forecast',
            'traffic_info'
        ));
    }
}

