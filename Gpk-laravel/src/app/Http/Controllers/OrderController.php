<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * 注文一覧表示
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Order::where('user_id', $user->id)
            ->with(['service', 'reservation', 'orderDetails.serviceOption']);
        
        // ステータスフィルター
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // タイプフィルター
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('orders.index', compact('orders'));
    }
    
    /**
     * 注文詳細表示
     */
    public function show(Order $order)
    {
        $user = Auth::user();
        
        // 自分の注文か確認
        if ($order->user_id !== $user->id) {
            abort(403, 'この注文を表示する権限がありません');
        }
        
        $order->load(['service', 'reservation', 'orderDetails.serviceOption', 'orderDetails.service']);
        
        return view('orders.show', compact('order'));
    }
}

