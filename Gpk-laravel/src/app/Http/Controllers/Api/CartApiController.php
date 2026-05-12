<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\CartDetailResource;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Service;
use App\Models\ServiceOption;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartApiController extends Controller
{
    /**
     * カート一覧取得
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $cart = Cart::with([
            'cartDetails.service',
            'cartDetails.serviceOption'
        ])->where('user_id', $user->id)->first();
        
        if (!$cart || $cart->cartDetails->count() == 0) {
            return response()->json([
                'cart' => null,
                'total_price' => 0,
                'items' => []
            ]);
        }
        
        return new CartResource($cart);
    }
    
    /**
     * カートに追加
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'service_option_id' => 'nullable|exists:service_options,id',
            'quantity' => 'required|integer|min:1',
            'reservation_id' => 'nullable|exists:reservations,id',
        ]);
        
        $user = $request->user();
        $service = Service::findOrFail($validated['service_id']);
        
        // 最小注文数チェック
        if ($service->minimum && $validated['quantity'] < $service->minimum) {
            return response()->json([
                'error' => "最小注文数は{$service->minimum}{$service->unit}です"
            ], 422);
        }
        
        // 在庫チェック
        if ($service->stock > 0 && $service->stock < $validated['quantity']) {
            return response()->json([
                'error' => '在庫が不足しています'
            ], 422);
        }
        
        // 価格計算
        $price = $service->price;
        if ($validated['service_option_id']) {
            $option = ServiceOption::findOrFail($validated['service_option_id']);
            $price += $option->price;
        }
        
        DB::beginTransaction();
        
        try {
            // カート取得または作成
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            
            // カート明細追加
            $cartDetail = CartDetail::create([
                'cart_id' => $cart->id,
                'service_id' => $service->id,
                'service_option_id' => $validated['service_option_id'] ?? null,
                'price' => $price,
                'quantity' => $validated['quantity'],
                'total_price' => $price * $validated['quantity'],
            ]);
            
            DB::commit();
            
            // リレーションをロード
            $cartDetail->load(['service', 'serviceOption']);
            
            return response()->json([
                'message' => 'カートに追加しました',
                'data' => new CartDetailResource($cartDetail)
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cart Add API Error: ' . $e->getMessage());
            return response()->json(['error' => 'カートへの追加に失敗しました'], 500);
        }
    }
    
    /**
     * カート明細削除
     */
    public function remove(Request $request, $id)
    {
        $cartDetail = CartDetail::findOrFail($id);
        
        // 権限チェック
        if ($cartDetail->cart->user_id != $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $cartDetail->delete();
        
        return response()->json([
            'message' => 'カートから削除しました'
        ]);
    }
    
    /**
     * カート数量更新
     */
    public function update(Request $request, $id)
    {
        $cartDetail = CartDetail::findOrFail($id);
        
        // 権限チェック
        if ($cartDetail->cart->user_id != $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        $service = $cartDetail->service;
        
        // 最小注文数チェック
        if ($service->minimum && $validated['quantity'] < $service->minimum) {
            return response()->json([
                'error' => "最小注文数は{$service->minimum}{$service->unit}です"
            ], 422);
        }
        
        // 在庫チェック
        if ($service->stock > 0 && $validated['quantity'] > $service->stock) {
            return response()->json([
                'error' => '在庫が不足しています'
            ], 422);
        }
        
        $cartDetail->quantity = $validated['quantity'];
        $cartDetail->total_price = $cartDetail->price * $validated['quantity'];
        $cartDetail->save();
        
        // リレーションをロード
        $cartDetail->load(['service', 'serviceOption']);
        
        return response()->json([
            'message' => '数量を更新しました',
            'data' => new CartDetailResource($cartDetail)
        ]);
    }
    
    /**
     * カート決済（注文確定）
     */
    public function checkout(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'reservation_id' => 'nullable|exists:reservations,id',
            'payment' => 'nullable|integer|in:0,1',
        ]);
        
        $cart = Cart::with('cartDetails.service')->where('user_id', $user->id)->first();
        
        if (!$cart || $cart->cartDetails->count() == 0) {
            return response()->json(['error' => 'カートが空です'], 422);
        }
        
        // 予約IDの権限チェック
        if ($validated['reservation_id']) {
            $reservation = \App\Models\Reservation::findOrFail($validated['reservation_id']);
            if ($reservation->user_id != $user->id && $reservation->owner_id != $user->id) {
                return response()->json(['error' => '予約情報へのアクセス権限がありません'], 403);
            }
        }
        
        DB::beginTransaction();
        
        try {
            $orders = [];
            
            // 各カート明細を注文に変換
            foreach ($cart->cartDetails as $detail) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'reservation_id' => $validated['reservation_id'] ?? null,
                    'service_id' => $detail->service_id,
                    'price' => $detail->price,
                    'quantity' => $detail->quantity,
                    'total_price' => $detail->total_price,
                    'payment' => $validated['payment'] ?? 0,
                    'type' => 2, // 現地注文
                    'status' => 1,
                ]);
                
                OrderDetail::create([
                    'order_id' => $order->id,
                    'service_id' => $detail->service_id,
                    'service_option_id' => $detail->service_option_id,
                    'price' => $detail->price,
                    'quantity' => $detail->quantity,
                    'total_price' => $detail->total_price,
                ]);
                
                $orders[] = $order;
            }
            
            // カート削除
            $cart->cartDetails()->delete();
            $cart->delete();
            
            DB::commit();
            
            // リレーションをロード
            foreach ($orders as $order) {
                $order->load(['service', 'orderDetails.service', 'orderDetails.serviceOption']);
            }
            
            return response()->json([
                'message' => '注文が完了しました',
                'orders' => OrderResource::collection($orders)
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cart Checkout API Error: ' . $e->getMessage());
            return response()->json(['error' => '注文に失敗しました'], 500);
        }
    }
}


