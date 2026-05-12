<?php

namespace App\Http\Controllers;

use App\Models\ReservationLog;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationLogController extends Controller
{
    /**
     * 予約ログ一覧表示
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = ReservationLog::where('user_id', $user->id)
            ->with('reservation');
        
        // 予約IDフィルター
        if ($request->has('reservation_id')) {
            $query->where('reservation_id', $request->reservation_id);
        }
        
        // アクションタイプフィルター
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }
        
        $reservation_logs = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('reservation-logs.index', compact('reservation_logs'));
    }
    
    /**
     * 予約ログ詳細表示
     */
    public function show(ReservationLog $reservationLog)
    {
        $user = Auth::user();
        
        // 自分のログか確認
        if ($reservationLog->user_id !== $user->id) {
            abort(403, 'このログを表示する権限がありません');
        }
        
        $reservationLog->load('reservation');
        
        return view('reservation-logs.show', compact('reservationLog'));
    }
}

