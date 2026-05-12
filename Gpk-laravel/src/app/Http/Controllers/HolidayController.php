<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidayController extends Controller
{
    /**
     * 休日一覧表示
     */
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        
        $query = Holiday::whereYear('date', $year);
        
        if ($month) {
            $query->whereMonth('date', $month);
        }
        
        $holidays = $query->orderBy('date', 'asc')->get();
        
        return view('holidays.index', compact('holidays', 'year', 'month'));
    }
    
    /**
     * 休日詳細表示
     */
    public function show(Holiday $holiday)
    {
        return view('holidays.show', compact('holiday'));
    }
}

