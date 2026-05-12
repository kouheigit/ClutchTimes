<?php

namespace App\Http\Controllers;

use App\Models\UserPointLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPointLogController extends Controller
{
    /**
     * ポイントログ一覧表示
     */
    public function index(Request $request)
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
        
        $point_logs = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('user-point-logs.index', compact('point_logs'));
    }
    
    /**
     * ポイントログ詳細表示
     */
    public function show(UserPointLog $userPointLog)
    {
        $user = Auth::user();
        
        // 自分のログか確認
        if ($userPointLog->user_id !== $user->id) {
            abort(403, 'このログを表示する権限がありません');
        }
        
        return view('user-point-logs.show', compact('userPointLog'));
    }
}

