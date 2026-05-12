<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Reservation;
use App\Mail\OrderConfirmMail;
use Illuminate\Http\Request;
use App\Http\Requests\CartRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    /**
     * カート一覧
     */
    public function index()
    {
        $user = Auth::user();
        
        $cart = Cart::with([
            'cartDetails.service',
            'cartDetails.serviceOption'
        ])->where('user_id', $user->id)->first();
        
        if (!$cart || $cart->cartDetails->count() == 0) {
            return view('cart.index', [
                'cart' => null, 
                'total_price' => 0,
                'last_reservation' => Reservation::getLastReservation($user->id)
            ]);
        }
        
        $total_price = $cart->cartDetails->sum('total_price');
        
        // 最新予約
        $last_reservation = Reservation::getLastReservation($user->id);
        
        return view('cart.index', compact('cart', 'total_price', 'last_reservation'));
    }
    
    /**
     * カート明細削除
     */
    public function delete(CartDetail $cart_detail)
    {
        if ($cart_detail->cart->user_id != Auth::id()) {
            abort(403);
        }
        
        $cart_detail->delete();
        
        return redirect()->route('cart.index')
            ->with('success', 'カートから削除しました');
    }
    
    /**
     * カート数量更新
     */
    public function update(Request $request, CartDetail $cart_detail)
    {
        if ($cart_detail->cart->user_id != Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $service = $cart_detail->service;

        // 最小注文数チェック
        if ($service->minimum && $validated['quantity'] < $service->minimum) {
            return back()->withErrors(['quantity' => "最小注文数は{$service->minimum}{$service->unit}です"]);
        }

        // 在庫チェック
        if ($service->stock > 0 && $validated['quantity'] > $service->stock) {
            return back()->withErrors(['quantity' => '在庫が不足しています']);
        }

        $cart_detail->quantity = $validated['quantity'];
        $cart_detail->total_price = $cart_detail->price * $validated['quantity'];
        $cart_detail->save();

        return redirect()->route('cart.index')
            ->with('success', 'カート数量を更新しました');
    }
    
    /**
     * 確認画面
     */
    public function confirm(Cart $cart)
    {
        if ($cart->user_id != Auth::id()) {
            abort(403);
        }
        
        $cart->load([
            'cartDetails.service',
            'cartDetails.serviceOption'
        ]);
        
        $total_price = $cart->cartDetails->sum('total_price');
        
        $last_reservation = Reservation::getLastReservation(Auth::id());
        
        return view('cart.confirm', compact('cart', 'total_price', 'last_reservation'));
    }
    
    /**
     * 注文確定（決済なし版）
     */
    public function store(Cart $cart, CartRequest $request)
    {
        if ($cart->user_id != Auth::id()) {
            abort(403);
        }
        
        // バリデーションはCartRequestで実行済み
        
        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            
            // 予約IDの権限チェック
            if ($request->reservation_id) {
                $reservation = Reservation::findOrFail($request->reservation_id);
                if ($reservation->user_id != $user->id && $reservation->owner_id != $user->id) {
                    throw new \Exception('予約情報へのアクセス権限がありません');
                }
            }
            
            // 各カート明細を注文に変換
            $orders = [];
            foreach ($cart->cartDetails as $detail) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'reservation_id' => $request->reservation_id,
                    'service_id' => $detail->service_id,
                    'price' => $detail->price,
                    'quantity' => $detail->quantity,
                    'total_price' => $detail->total_price,
                    'payment' => $request->payment ?? 0,
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
            
            // メール送信（各注文に対して）
            try {
                foreach ($orders as $order) {
                    Mail::to($user->email)->send(new OrderConfirmMail($order));
                }
            } catch (\Exception $e) {
                Log::warning('Mail sending failed: ' . $e->getMessage());
                // メール送信失敗は注文処理を止めない
            }
            
            return redirect()->route('cart.complete')
                ->with('success', '注文が完了しました');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cart Order Error: ' . $e->getMessage());
            return back()->withErrors(['error' => '注文に失敗しました: ' . $e->getMessage()]);
        }
    }
    
    /**
     * 完了画面
     */
    public function complete()
    {
        return view('cart.complete');
    }
}
