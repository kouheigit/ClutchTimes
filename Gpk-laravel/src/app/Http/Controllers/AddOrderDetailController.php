<?php

namespace App\Http\Controllers;

use App\Models\AddOrderDetail;
use App\Models\AddOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddOrderDetailController extends Controller
{
    /**
     * 追加注文明細一覧表示
     */
    public function index(Request $request)
    {
        $add_order_id = $request->input('add_order_id');
        
        $query = AddOrderDetail::with(['addOrder', 'service', 'serviceOption']);
        
        if ($add_order_id) {
            $query->where('add_order_id', $add_order_id);
        }
        
        $add_order_details = $query->orderBy('created_at', 'desc')->get();
        
        return view('add-order-details.index', compact('add_order_details', 'add_order_id'));
    }
    
    /**
     * 追加注文明細詳細表示
     */
    public function show(AddOrderDetail $addOrderDetail)
    {
        $addOrderDetail->load(['addOrder', 'service', 'serviceOption']);
        
        return view('add-order-details.show', compact('addOrderDetail'));
    }
}

