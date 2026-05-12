<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarResource;
use App\Http\Resources\ReservationResource;
use App\Models\Calendar;
use App\Models\Reservation;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarApiController extends Controller
{
    /**
     * 利用可能なカレンダー取得
     */
    public function available(Request $request)
    {
        $user = $request->user();
        
        $query = Calendar::where('user_id', $user->id)
            ->where('status', 1) // 予約可能
            ->with('hotel');
        
        // 日付範囲フィルター
        if ($request->has('from_date')) {
            $query->where('end_date', '>=', $request->from_date);
        }
        
        if ($request->has('to_date')) {
            $query->where('start_date', '<=', $request->to_date);
        }
        
        $calendars = $query->orderBy('start_date', 'asc')->get();
        
        return response()->json([
            'calendars' => CalendarResource::collection($calendars)
        ]);
    }
    
    /**
     * 月次カレンダー取得
     */
    public function monthly(Request $request, $year, $month)
    {
        $user = $request->user();
        $date = Carbon::createFromDate($year, $month, 1);
        
        // 該当月のカレンダー取得
        $calendars = Calendar::where('user_id', $user->id)
            ->where(function($query) use ($year, $month) {
                $query->whereYear('start_date', $year)
                      ->whereMonth('start_date', $month);
            })
            ->orWhere(function($query) use ($year, $month) {
                $query->whereYear('end_date', $year)
                      ->whereMonth('end_date', $month);
            })
            ->with(['hotel'])
            ->orderBy('start_date', 'asc')
            ->get();
        
        // 休日取得
        $holidays = Holiday::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->map(function($holiday) {
                return Carbon::parse($holiday->date)->format('Y-m-d');
            })
            ->toArray();
        
        // 予約済み日程
        $reservations = Reservation::where('owner_id', $user->id)
            ->where(function($query) use ($date) {
                $query->where(function($q) use ($date) {
                    $q->whereYear('checkin_date', $date->year)
                      ->whereMonth('checkin_date', $date->month);
                })->orWhere(function($q) use ($date) {
                    $q->whereYear('checkout_date', $date->year)
                      ->whereMonth('checkout_date', $date->month);
                })->orWhere(function($q) use ($date) {
                    $firstDay = $date->copy()->startOfMonth()->format('Y-m-d');
                    $lastDay = $date->copy()->endOfMonth()->format('Y-m-d');
                    $q->where('checkin_date', '<=', $lastDay)
                      ->where('checkout_date', '>=', $firstDay);
                });
            })
            ->with(['hotel'])
            ->get();
        
        return response()->json([
            'year' => $year,
            'month' => $month,
            'calendars' => CalendarResource::collection($calendars),
            'holidays' => $holidays,
            'reservations' => ReservationResource::collection($reservations),
        ]);
    }
}


