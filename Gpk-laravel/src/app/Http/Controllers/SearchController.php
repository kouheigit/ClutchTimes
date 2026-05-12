<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Service;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * 検索結果表示
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $keyword = $request->input('keyword');
        $type = $request->input('type', 'all'); // all, hotel, service, reservation
        
        $results = [];
        
        if ($type === 'all' || $type === 'hotel') {
            $hotels = Hotel::where('name', 'like', "%{$keyword}%")
                ->orWhere('address', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%")
                ->where('status', 1)
                ->limit(10)
                ->get();
            $results['hotels'] = $hotels;
        }
        
        if ($type === 'all' || $type === 'service') {
            $services = Service::where('title', 'like', "%{$keyword}%")
                ->orWhere('body', 'like', "%{$keyword}%")
                ->where('status', 1)
                ->limit(10)
                ->get();
            $results['services'] = $services;
        }
        
        if ($type === 'all' || $type === 'reservation') {
            $reservations = Reservation::where('owner_id', $user->id)
                ->where(function($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")
                          ->orWhere('note', 'like', "%{$keyword}%");
                })
                ->limit(10)
                ->get();
            $results['reservations'] = $reservations;
        }
        
        return view('search.index', compact('results', 'keyword', 'type'));
    }
}

