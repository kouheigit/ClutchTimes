<?php

namespace App\Http\Controllers;

use App\Models\VeritransLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VeritransLogController extends Controller
{
    /**
     * Veritransログ一覧表示
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = VeritransLog::where('user_id', $user->id)
            ->with(['reservation']);
        
        // タイプフィルター
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        // ステータスフィルター
        if ($request->has('txn_status')) {
            $query->where('txn_status', $request->txn_status);
        }
        
        // 予約IDフィルター
        if ($request->has('reservation_id')) {
            $query->where('reservation_id', $request->reservation_id);
        }
        
        $veritrans_logs = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('veritrans-logs.index', compact('veritrans_logs'));
    }
    
    /**
     * Veritransログ詳細表示
     */
    public function show(VeritransLog $veritransLog)
    {
        $user = Auth::user();
        
        // 自分のログか確認
        if ($veritransLog->user_id !== $user->id) {
            abort(403, 'このログを表示する権限がありません');
        }
        
        $veritransLog->load('reservation');
        
        return view('veritrans-logs.show', compact('veritransLog'));
    }
}

