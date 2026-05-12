<?php

namespace App\Http\Controllers;

use App\Models\AddOrder;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddOrderController extends Controller
{
    /**
     * 追加注文一覧表示
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = AddOrder::where('user_id', $user->id)
            ->with(['reservation', 'addOrderDetails.service', 'addOrderDetails.serviceOption']);
        
        // ステータスフィルター
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // 予約IDフィルター
        if ($request->has('reservation_id')) {
            $query->where('reservation_id', $request->reservation_id);
        }
        
        $add_orders = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('add-orders.index', compact('add_orders'));
    }
    
    /**
     * 追加注文詳細表示
     */
    public function show(AddOrder $addOrder)
    {
        $user = Auth::user();
        
        // 自分の追加注文か確認
        if ($addOrder->user_id !== $user->id) {
            abort(403, 'この追加注文を表示する権限がありません');
        }
        
        $addOrder->load(['reservation', 'addOrderDetails.service', 'addOrderDetails.serviceOption']);
        
        return view('add-orders.show', compact('addOrder'));
    }
}

