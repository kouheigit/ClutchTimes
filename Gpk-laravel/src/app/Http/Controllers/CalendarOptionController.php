<?php

namespace App\Http\Controllers;

use App\Models\CalendarOption;
use App\Models\Calendar;
use Illuminate\Http\Request;

class CalendarOptionController extends Controller
{
    /**
     * カレンダーオプション一覧表示
     */
    public function index(Request $request)
    {
        $calendar_id = $request->input('calendar_id');
        
        $query = CalendarOption::with('calendar');
        
        if ($calendar_id) {
            $query->where('calendar_id', $calendar_id);
        }
        
        $calendar_options = $query->orderBy('sort', 'asc')->get();
        
        return view('calendar-options.index', compact('calendar_options', 'calendar_id'));
    }
    
    /**
     * カレンダーオプション詳細表示
     */
    public function show(CalendarOption $calendarOption)
    {
        $calendarOption->load('calendar');
        
        return view('calendar-options.show', compact('calendarOption'));
    }
}

