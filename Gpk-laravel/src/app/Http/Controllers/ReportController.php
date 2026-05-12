<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * レポート一覧表示
     */
    public function index()
    {
        return view('reports.index');
    }
    
    /**
     * 予約レポート表示
     */
    public function reservations(Request $request)
    {
        $user = Auth::user();
        
        $from_date = $request->input('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $to_date = $request->input('to_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $reservations = Reservation::where('owner_id', $user->id)
            ->whereBetween('checkin_date', [$from_date, $to_date])
            ->with(['hotel', 'user'])
            ->orderBy('checkin_date', 'asc')
            ->get();
        
        return view('reports.reservations', compact('reservations', 'from_date', 'to_date'));
    }
    
    /**
     * 注文レポート表示
     */
    public function orders(Request $request)
    {
        $user = Auth::user();
        
        $from_date = $request->input('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $to_date = $request->input('to_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $orders = Order::where('user_id', $user->id)
            ->whereBetween('created_at', [$from_date, $to_date])
            ->with(['service', 'reservation'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('reports.orders', compact('orders', 'from_date', 'to_date'));
    }
}

