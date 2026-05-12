<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Reservation;
use App\Models\Holiday;
use App\Consts\ReservationConst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * カレンダー表示
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 年月指定（デフォルトは今月）
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        
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
        
        // 予約済み日程（該当月に含まれる予約を取得）
        $reservations = Reservation::where('owner_id', $user->id)
            ->where(function($query) use ($date) {
                $query->where(function($q) use ($date) {
                    // チェックインが該当月
                    $q->whereYear('checkin_date', $date->year)
                      ->whereMonth('checkin_date', $date->month);
                })->orWhere(function($q) use ($date) {
                    // チェックアウトが該当月
                    $q->whereYear('checkout_date', $date->year)
                      ->whereMonth('checkout_date', $date->month);
                })->orWhere(function($q) use ($date) {
                    // 該当月を含む予約
                    $firstDay = $date->copy()->startOfMonth()->format('Y-m-d');
                    $lastDay = $date->copy()->endOfMonth()->format('Y-m-d');
                    $q->where('checkin_date', '<=', $lastDay)
                      ->where('checkout_date', '>=', $firstDay);
                });
            })
            ->with(['hotel'])
            ->get();
        
        // 前月・次月
        $prevMonth = $date->copy()->subMonth();
        $nextMonth = $date->copy()->addMonth();
        
        return view('calendar.index', compact(
            'calendars',
            'holidays',
            'reservations',
            'year',
            'month',
            'date',
            'prevMonth',
            'nextMonth'
        ));
    }
    
    /**
     * 日付詳細
     */
    public function detail($year, $month, $day)
    {
        $user = Auth::user();
        $date = Carbon::createFromDate($year, $month, $day);
        
        // その日のカレンダー情報取得
        $calendar = Calendar::where('user_id', $user->id)
            ->where(function($query) use ($date) {
                $query->where('start_date', '<=', $date->format('Y-m-d'))
                      ->where('end_date', '>=', $date->format('Y-m-d'));
            })
            ->with(['hotel'])
            ->first();
        
        // その日の予約
        $reservations = Reservation::where('owner_id', $user->id)
            ->where('checkin_date', '<=', $date->format('Y-m-d'))
            ->where('checkout_date', '>=', $date->format('Y-m-d'))
            ->with(['hotel', 'user'])
            ->get();
        
        return view('calendar.detail', compact('calendar', 'reservations', 'date'));
    }
}
