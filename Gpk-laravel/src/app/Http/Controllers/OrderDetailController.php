<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderDetailController extends Controller
{
    /**
     * 注文明細一覧表示
     */
    public function index(Request $request)
    {
        $order_id = $request->input('order_id');
        
        $query = OrderDetail::with(['order', 'service', 'serviceOption']);
        
        if ($order_id) {
            $query->where('order_id', $order_id);
        }
        
        $order_details = $query->orderBy('created_at', 'desc')->get();
        
        return view('order-details.index', compact('order_details', 'order_id'));
    }
    
    /**
     * 注文明細詳細表示
     */
    public function show(OrderDetail $orderDetail)
    {
        $user = Auth::user();
        
        // 自分の注文明細か確認
        if ($orderDetail->order && $orderDetail->order->user_id !== $user->id) {
            abort(403, 'この注文明細を表示する権限がありません');
        }
        
        $orderDetail->load(['order', 'service', 'serviceOption']);
        
        return view('order-details.show', compact('orderDetail'));
    }
}

