<?php

namespace App\Http\Controllers;

use App\Models\UserPoint;
use App\Models\UserPointLog;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PointController extends Controller
{
    private $point_service;
    
    public function __construct(PointService $point_service)
    {
        $this->point_service = $point_service;
    }
    
    /**
     * ポイント一覧表示
     */
    public function index()
    {
        $user = Auth::user();
        
        // ポイント残高取得
        $user_point = $this->point_service->getAvailablePoints($user->id);
        
        // ポイント残高詳細（有効期限別）
        $pointbalance = UserPoint::where('user_id', $user->id)
            ->where('to', '>=', Carbon::now()->format('Y-m-d'))
            ->where('point', '>', 0)
            ->orderBy('to', 'asc')
            ->get();
        
        // ポイント履歴
        $pointlogs = UserPointLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('points.index', compact('user_point', 'pointbalance', 'pointlogs'));
    }
    
    /**
     * ポイント履歴表示
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $query = UserPointLog::where('user_id', $user->id);
        
        // タイプフィルター
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        // 日付範囲フィルター
        if ($request->has('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }
        
        if ($request->has('to_date')) {
            $query->where('created_at', '<=', $request->to_date);
        }
        
        $pointlogs = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('points.history', compact('pointlogs'));
    }
}

