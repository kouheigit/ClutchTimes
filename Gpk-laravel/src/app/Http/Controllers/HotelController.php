<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Service;
use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    /**
     * ホテル一覧表示
     */
    public function index()
    {
        $user = Auth::user();
        
        // ユーザーに関連するホテルを取得
        $hotels = Hotel::whereHas('users', function($query) use ($user) {
            $query->where('users.id', $user->id);
        })
        ->orWhere('status', 1) // 公開中のホテルも表示
        ->with(['services', 'calendars'])
        ->orderBy('name', 'asc')
        ->get();
        
        return view('hotels.index', compact('hotels'));
    }
    
    /**
     * ホテル詳細表示
     */
    public function show(Hotel $hotel)
    {
        // ホテルに関連するサービス取得
        $services = Service::where('hotel_id', $hotel->id)
            ->where('status', 1)
            ->with('serviceOptions')
            ->orderBy('sort', 'asc')
            ->get();
        
        // ホテルに関連するカレンダー取得（2年分）
        $start_date = now()->firstOfYear();
        $end_date = $start_date->copy()->addYears(2)->endOfYear();
        
        $calendars = Calendar::where('hotel_id', $hotel->id)
            ->whereBetween('start_date', [$start_date, $end_date])
            ->orderBy('start_date', 'asc')
            ->get();
        
        return view('hotels.show', compact('hotel', 'services', 'calendars'));
    }
}

