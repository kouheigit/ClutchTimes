<?php

namespace App\Http\Controllers;

use App\Models\ReleaseLog;
use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReleaseLogController extends Controller
{
    /**
     * リリースログ一覧表示
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = ReleaseLog::where('user_id', $user->id)
            ->with('calendar');
        
        // カレンダーIDフィルター
        if ($request->has('calendar_id')) {
            $query->where('calendar_id', $request->calendar_id);
        }
        
        // アクションタイプフィルター
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }
        
        $release_logs = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('release-logs.index', compact('release_logs'));
    }
    
    /**
     * リリースログ詳細表示
     */
    public function show(ReleaseLog $releaseLog)
    {
        $user = Auth::user();
        
        // 自分のログか確認
        if ($releaseLog->user_id !== $user->id) {
            abort(403, 'このログを表示する権限がありません');
        }
        
        $releaseLog->load('calendar');
        
        return view('release-logs.show', compact('releaseLog'));
    }
}

