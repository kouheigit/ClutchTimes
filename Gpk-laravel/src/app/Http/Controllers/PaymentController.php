<?php

namespace App\Http\Controllers;

use App\Models\VeritransLog;
use App\Models\Reservation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * 決済履歴一覧表示
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = VeritransLog::where('user_id', $user->id)
            ->with(['reservation']);
        
        // ステータスフィルター
        if ($request->has('txn_status')) {
            $query->where('txn_status', $request->txn_status);
        }
        
        $payments = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('payments.index', compact('payments'));
    }
    
    /**
     * 決済詳細表示
     */
    public function show(VeritransLog $veritransLog)
    {
        $user = Auth::user();
        
        // 自分の決済か確認
        if ($veritransLog->user_id !== $user->id) {
            abort(403, 'この決済を表示する権限がありません');
        }
        
        $veritransLog->load(['reservation', 'user']);
        
        return view('payments.show', compact('veritransLog'));
    }
}

