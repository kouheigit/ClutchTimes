<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\UserPoint;
use App\Models\UserPointLog;
use App\Models\Freeday;
use App\Models\Order;
use App\Services\FreedayService;
use App\Services\PointService;
use App\Consts\ReservationConst;
use App\Consts\UserConst;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MypageController extends Controller
{
    private $freeday_service;
    private $point_service;
    
    public function __construct(FreedayService $freeday_service, PointService $point_service)
    {
        $this->freeday_service = $freeday_service;
        $this->point_service = $point_service;
    }
    
    /**
     * マイページ表示
     */
    public function index()
    {
        $user = Auth::user();
        
        // ポイント残高取得
        $user_point = $this->point_service->getAvailablePoints($user->id);
        
        // ポイント残高詳細（有効期限別）
        $pointbalance = UserPoint::where('user_id', $user->id)
            ->where('to', '>=', Carbon::now()->format('Y-m-d'))
            ->where('point', '>', 0)
            ->orderBy('to', 'asc')
            ->get();
        
        // FREEDAYS取得
        $freedays = $this->freeday_service->getFreedays($user);
        
        // 今後の予約取得
        $reservations = Reservation::where('owner_id', $user->id)
            ->with(['hotel', 'user', 'calendar'])
            ->whereIn('status', [
                ReservationConst::STATUS_APPLYING,
                ReservationConst::STATUS_UNDER_RESERVATION,
                ReservationConst::STATUS_RESERVED
            ])
            ->where('checkin_date', '>=', Carbon::now()->format('Y-m-d'))
            ->orderBy('checkin_date', 'asc')
            ->limit(10)
            ->get();
        
        return view('mypage.index', compact(
            'user',
            'user_point',
            'pointbalance',
            'freedays',
            'reservations'
        ));
    }
    
    /**
     * プロフィール編集画面
     */
    public function edit()
    {
        $user = Auth::user();
        
        return view('mypage.edit', compact('user'));
    }
    
    /**
     * プロフィール更新
     */
    public function update(UserRequest $request)
    {
        // バリデーションはUserRequestで実行済み
        $user = Auth::user();
        $data = $request->validated();
        
        // パスワードが入力されている場合のみ更新
        if (!empty($data['password'])) {
            $data['password'] = \Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        
        $user->update($data);
        
        return redirect()->route('mypage.index')
            ->with('success', 'プロフィールを更新しました');
    }
    
    /**
     * 予約履歴
     */
    public function history()
    {
        $user = Auth::user();
        
        $reservations = Reservation::where('owner_id', $user->id)
            ->with(['hotel', 'user', 'calendar', 'orders.orderDetails.service', 'addOrders.addOrderDetails.service'])
            ->orderBy('checkin_date', 'desc')
            ->paginate(20);
        
        return view('mypage.history', compact('reservations'));
    }
    
    /**
     * ポイント履歴
     */
    public function pointlog()
    {
        $user = Auth::user();
        
        // ポイント残高
        $user_point = $this->point_service->getAvailablePoints($user->id);
        
        // ポイント残高詳細
        $pointbalance = UserPoint::where('user_id', $user->id)
            ->where('to', '>=', Carbon::now()->format('Y-m-d'))
            ->where('point', '>', 0)
            ->orderBy('to', 'asc')
            ->get();
        
        // ポイント履歴
        $pointlogs = UserPointLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('mypage.pointlog', compact(
            'user_point',
            'pointbalance',
            'pointlogs'
        ));
    }
    
    /**
     * 注文一覧
     */
    public function orders()
    {
        $user = Auth::user();
        
        $orders = Order::where('user_id', $user->id)
            ->with(['service', 'reservation', 'orderDetails.serviceOption'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('mypage.orders', compact('orders'));
    }
    
    /**
     * 注文詳細
     */
    public function orderShow(Order $order)
    {
        $user = Auth::user();
        
        // 自分の注文か確認
        if ($order->user_id !== $user->id) {
            abort(403, 'この注文を表示する権限がありません');
        }
        
        $order->load(['service', 'reservation', 'orderDetails.serviceOption']);
        
        return view('mypage.order-show', compact('order'));
    }
    
    /**
     * 予約一覧（フィルター付き）
     */
    public function reservations(Request $request)
    {
        $user = Auth::user();
        
        $query = Reservation::where('owner_id', $user->id)
            ->with(['hotel', 'user', 'calendar', 'orders.orderDetails.service', 'addOrders.addOrderDetails.service']);
        
        // ステータスフィルター
        $status = $request->get('status');
        if ($status === 'active') {
            $query->whereIn('status', [
                ReservationConst::STATUS_APPLYING,
                ReservationConst::STATUS_UNDER_RESERVATION,
                ReservationConst::STATUS_RESERVED
            ])
            ->where('checkin_date', '>=', Carbon::now()->format('Y-m-d'));
        } elseif ($status === 'past') {
            $query->where('checkout_date', '<', Carbon::now()->format('Y-m-d'))
                ->where('status', '!=', ReservationConst::STATUS_CANCEL);
        } elseif ($status === 'cancelled') {
            $query->where('status', ReservationConst::STATUS_CANCEL);
        }
        
        $reservations = $query->orderBy('checkin_date', 'desc')
            ->paginate(20);
        
        return view('mypage.reservations', compact('reservations'));
    }
}

