<?php

namespace App\Http\Controllers;

use App\Models\CartDetail;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartDetailController extends Controller
{
    /**
     * カート明細一覧表示
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $cart_id = $request->input('cart_id');
        
        $query = CartDetail::with(['cart', 'service', 'serviceOption'])
            ->whereHas('cart', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        
        if ($cart_id) {
            $query->where('cart_id', $cart_id);
        }
        
        $cart_details = $query->orderBy('created_at', 'desc')->get();
        
        return view('cart-details.index', compact('cart_details', 'cart_id'));
    }
    
    /**
     * カート明細詳細表示
     */
    public function show(CartDetail $cartDetail)
    {
        $user = Auth::user();
        
        // 自分のカート明細か確認
        if ($cartDetail->cart && $cartDetail->cart->user_id !== $user->id) {
            abort(403, 'このカート明細を表示する権限がありません');
        }
        
        $cartDetail->load(['cart', 'service', 'serviceOption']);
        
        return view('cart-details.show', compact('cartDetail'));
    }
}

