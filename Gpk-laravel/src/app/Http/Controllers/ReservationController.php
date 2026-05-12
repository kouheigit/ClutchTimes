<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Calendar;
use App\Models\Freeday;
use App\Models\Hotel;
use App\Models\Service;
use App\Models\ServiceOption;
use App\Models\TmpOrderDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ReservationLog;
use App\Consts\ReservationConst;
use App\Services\FreedayService;
use App\Services\VeritransService;
use App\Services\PointService;
use App\Mail\ReservationConfirmMail;
use App\Mail\AdminMail;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationStoreRequest;
use App\Http\Requests\ReservationUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ReservationController extends Controller
{
    private $freeday_service;
    private $veritrans_service;
    private $point_service;
    
    public function __construct(FreedayService $freeday_service, VeritransService $veritrans_service, PointService $point_service)
    {
        $this->freeday_service = $freeday_service;
        $this->veritrans_service = $veritrans_service;
        $this->point_service = $point_service;
    }
    
    /**
     * 予約一覧表示
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 2年分のFIXDAYを取得
        $start_date = Carbon::now()->firstOfYear();
        $end_date = $start_date->copy()->addYears(2)->endOfYear();
        
        $calendars = Calendar::where('user_id', $user->id)
            ->whereBetween('start_date', [$start_date, $end_date])
            ->orderBy('start_date', 'asc')
            ->get();
        
        // FREEDAYSを取得
        $freedays = $this->freeday_service->getFreedays($user);
        
        // 予約を取得（N+1問題対策：リレーションを事前ロード）
        $query = Reservation::where('owner_id', $user->id)
            ->with(['hotel', 'user', 'calendar', 'orders.orderDetails.service', 'addOrders.addOrderDetails.service']);
        
        // ステータスフィルター
        $status_filter = $request->input('status', 'active'); // active: 有効な予約, all: 全て, past: 過去
        
        if ($status_filter === 'active') {
            // 有効な予約のみ（申請中、予約中、予約確定）
            $query->whereIn('status', [
                ReservationConst::STATUS_APPLYING,
                ReservationConst::STATUS_UNDER_RESERVATION,
                ReservationConst::STATUS_RESERVED
            ]);
        } elseif ($status_filter === 'past') {
            // 過去の予約（チェックアウト日が過去）
            $query->where('checkout_date', '<', Carbon::now()->format('Y-m-d'))
                ->whereIn('status', [
                    ReservationConst::STATUS_APPLYING,
                    ReservationConst::STATUS_UNDER_RESERVATION,
                    ReservationConst::STATUS_RESERVED
                ]);
        }
        // 'all'の場合は全ての予約を表示（キャンセル以外）
        
        $reservations = $query->orderBy('checkin_date', 'desc')->get();
        
        return view('reservation.index', compact('calendars', 'freedays', 'reservations', 'status_filter'));
    }
    
    /**
     * 予約作成画面
     */
    public function create(Request $request)
    {
        $calendar_id = $request->calendar_id;
        $fr = $request->fr; // フリーデイID
        
        if ($calendar_id) {
            // FIXDAY予約
            $calendar = Calendar::with('hotel')->findOrFail($calendar_id);
            
            // セッションに予約情報を保存
            session(['reservation_data' => [
                'hotel_id' => $calendar->hotel_id,
                'calendar_id' => $calendar->id,
                'checkin_date' => $calendar->start_date->format('Y-m-d'),
                'checkout_date' => $calendar->end_date->format('Y-m-d'),
                'days' => $calendar->start_date->diffInDays($calendar->end_date),
            ]]);
            
            return view('reservation.create', compact('calendar'));
        }
        
        if ($fr) {
            // FREEDAY予約
            $freeday = Freeday::findOrFail($fr);
            $user = Auth::user();
            
            // ユーザーに関連するホテルを取得（最初の1つ）
            $hotel = $user->hotels()->first();
            if (!$hotel) {
                // ホテルがない場合はデフォルトホテルを取得
                $hotel = Hotel::first();
            }
            
            if (!$hotel) {
                return redirect()->route('reservation.index')
                    ->withErrors(['error' => 'ホテル情報が見つかりません']);
            }
            
            // セッションに予約情報を保存（初期値のみ、フォーム送信時に更新）
            $checkinDate = $request->d ?? now()->format('Y-m-d');
            session(['reservation_data' => [
                'hotel_id' => $hotel->id,
                'freeday_id' => $freeday->id,
                'checkin_date' => $checkinDate,
                'checkout_date' => Carbon::parse($checkinDate)->addDay()->format('Y-m-d'),
                'days' => 1,
            ]]);
            
            return view('reservation.create_freeday', compact('freeday'));
        }
        
        abort(404);
    }
    
    /**
     * サービス選択画面
     */
    public function service(Request $request)
    {
        $user = Auth::user();
        
        // セッションから予約情報取得
        $reservation_data = session('reservation_data');
        
        if (!$reservation_data) {
            return redirect()->route('reservation.index');
        }
        
        // 予約情報をセッションに保存（人数情報を追加）
        // FIXDAYの場合はフォームから、FREEDAYの場合はセッションから取得
        if ($request->has('adult')) {
            $reservation_data['adult'] = $request->adult ?? 2;
            $reservation_data['child'] = $request->child ?? 0;
            $reservation_data['dog'] = $request->dog ?? 0;
            $reservation_data['note'] = $request->note ?? null;
        }
        
        // FREEDAYの場合は日程情報を更新
        if (isset($reservation_data['freeday_id']) && $request->has('checkin_date')) {
            $checkinDate = Carbon::parse($request->checkin_date);
            $days = $request->days ?? 1;
            $reservation_data['checkin_date'] = $checkinDate->format('Y-m-d');
            $reservation_data['checkout_date'] = $checkinDate->copy()->addDays($days)->format('Y-m-d');
            $reservation_data['days'] = $days;
            $reservation_data['adult'] = $request->adult ?? 2;
            $reservation_data['child'] = $request->child ?? 0;
            $reservation_data['dog'] = $request->dog ?? 0;
            $reservation_data['note'] = $request->note ?? null;
        }
        
        session(['reservation_data' => $reservation_data]);
        
        // 事前予約可能なサービス取得
        $services = Service::where('hotel_id', $reservation_data['hotel_id'])
            ->where('status', 1)
            ->where('tab', 1) // 事前予約タブ
            ->orderBy('sort', 'asc')
            ->with('serviceOptions')
            ->get();
        
        // 一時保存済みサービス取得
        $tmp_orders = TmpOrderDetail::where('user_id', $user->id)
            ->with(['service', 'serviceOption'])
            ->get();
        
        return view('reservation.service', compact('services', 'tmp_orders', 'reservation_data'));
    }
    
    /**
     * カートに追加
     */
    public function cart_add(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'service_option_id' => 'nullable|exists:service_options,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $service = Service::findOrFail($request->service_id);
        
        // 最小注文数チェック
        if ($service->minimum && $request->quantity < $service->minimum) {
            return back()->withErrors(['quantity' => "最小注文数は{$service->minimum}{$service->unit}です"]);
        }
        
        // 在庫チェック
        if ($service->stock > 0 && $service->stock < $request->quantity) {
            return back()->withErrors(['quantity' => '在庫が不足しています']);
        }
        
        // 価格計算
        $price = $service->price;
        if ($request->service_option_id) {
            $option = ServiceOption::findOrFail($request->service_option_id);
            $price += $option->price;
        }
        
        // 一時保存
        TmpOrderDetail::create([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
            'service_option_id' => $request->service_option_id,
            'price' => $price,
            'quantity' => $request->quantity,
            'total_price' => $price * $request->quantity,
            'type' => 1,
        ]);
        
        return redirect()->route('reservation.service')
            ->with('success', 'カートに追加しました');
    }
    
    /**
     * カート画面
     */
    public function cart(Request $request)
    {
        $user = Auth::user();
        $reservation_data = session('reservation_data');
        
        $tmp_orders = TmpOrderDetail::where('user_id', $user->id)
            ->with(['service', 'serviceOption'])
            ->get();
        
        $total_price = $tmp_orders->sum('total_price');
        
        return view('reservation.cart', compact('tmp_orders', 'total_price', 'reservation_data'));
    }
    
    /**
     * カート削除
     */
    public function cart_delete(TmpOrderDetail $tmp_order_detail)
    {
        if ($tmp_order_detail->user_id != Auth::id()) {
            abort(403);
        }
        
        $tmp_order_detail->delete();
        
        return redirect()->route('reservation.cart')
            ->with('success', 'カートから削除しました');
    }
    
    /**
     * カート数量更新
     */
    public function cart_update(Request $request, TmpOrderDetail $tmp_order_detail)
    {
        if ($tmp_order_detail->user_id != Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        $service = $tmp_order_detail->service;
        
        // 最小注文数チェック
        if ($service->minimum && $request->quantity < $service->minimum) {
            return back()->withErrors(['quantity' => "最小注文数は{$service->minimum}{$service->unit}です"]);
        }
        
        // 在庫チェック
        if ($service->stock > 0 && $service->stock < $request->quantity) {
            return back()->withErrors(['quantity' => '在庫が不足しています']);
        }
        
        // 数量と合計金額を更新
        $tmp_order_detail->quantity = $request->quantity;
        $tmp_order_detail->total_price = $tmp_order_detail->price * $request->quantity;
        $tmp_order_detail->save();
        
        return redirect()->route('reservation.cart')
            ->with('success', '数量を更新しました');
    }
    
    /**
     * 予約確認画面
     */
    public function confirm(Request $request)
    {
        $user = Auth::user();
        $reservation_data = session('reservation_data');
        
        if (!$reservation_data) {
            return redirect()->route('reservation.index')
                ->withErrors(['error' => '予約情報が見つかりません']);
        }
        
        $tmp_orders = TmpOrderDetail::where('user_id', $user->id)
            ->with(['service', 'serviceOption'])
            ->get();
        
        // カートが空の場合はサービス選択画面にリダイレクト
        if ($tmp_orders->count() == 0) {
            return redirect()->route('reservation.service')
                ->withErrors(['error' => 'カートに商品がありません。サービスを選択してください。']);
        }
        
        $service_total = $tmp_orders->sum('total_price');
        
        // 利用可能ポイント取得
        $available_points = $this->point_service->getAvailablePoints($user->id);
        
        // ポイント利用可能額（合計金額まで）
        $max_point_use = min($available_points, $service_total);
        
        // デフォルトの合計金額（ポイント未使用時）
        $total_price = $service_total;
        
        return view('reservation.confirm', compact(
            'reservation_data',
            'tmp_orders',
            'service_total',
            'available_points',
            'max_point_use',
            'total_price'
        ));
    }
    
    /**
     * 予約作成
     */
    public function store(ReservationStoreRequest $request)
    {
        // バリデーションはReservationStoreRequestで実行済み
        
        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            $reservation_data = session('reservation_data');
            
            if (!$reservation_data) {
                throw new \Exception('予約情報がありません');
            }
            
            // カートが空でないかチェック
            $tmp_orders = TmpOrderDetail::where('user_id', $user->id)->get();
            if ($tmp_orders->count() == 0) {
                throw new \Exception('カートに商品がありません');
            }
            
            // 予約作成
            $reservation = Reservation::create([
                'hotel_id' => $reservation_data['hotel_id'],
                'user_id' => $user->id,
                'owner_id' => $user->type == 2 ? $user->id : ($user->user_id ?? $user->id),
                'calendar_id' => $reservation_data['calendar_id'] ?? null,
                'checkin_date' => $reservation_data['checkin_date'],
                'checkout_date' => $reservation_data['checkout_date'],
                'days' => $reservation_data['days'],
                'adult' => $reservation_data['adult'],
                'child' => $reservation_data['child'] ?? 0,
                'dog' => $reservation_data['dog'] ?? 0,
                'name' => $user->name,
                'note' => $reservation_data['note'] ?? $request->note ?? null,
                'payment' => $request->payment ?? 0,
                'status' => ReservationConst::STATUS_UNDER_RESERVATION,
            ]);
            
            // サービス注文作成
            $total_price = 0;
            
            foreach ($tmp_orders as $tmp) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'reservation_id' => $reservation->id,
                    'service_id' => $tmp->service_id,
                    'price' => $tmp->price,
                    'quantity' => $tmp->quantity,
                    'total_price' => $tmp->total_price,
                    'payment' => $request->payment ?? 0,
                    'type' => 1,
                    'status' => 1,
                ]);
                
                OrderDetail::create([
                    'order_id' => $order->id,
                    'service_id' => $tmp->service_id,
                    'service_option_id' => $tmp->service_option_id,
                    'price' => $tmp->price,
                    'quantity' => $tmp->quantity,
                    'total_price' => $tmp->total_price,
                ]);
                
                $total_price += $tmp->total_price;
            }
            
            // ポイント利用処理
            $point_discount = 0;
            if ($request->use_point && $request->use_point > 0) {
                $available_points = $this->point_service->getAvailablePoints($user->id);
                
                if ($request->use_point > $available_points) {
                    throw new \Exception('ポイントが不足しています');
                }
                
                if ($request->use_point > $total_price) {
                    throw new \Exception('利用ポイントは合計金額以下にしてください');
                }
                
                // ポイント消費
                $this->point_service->usePoint(
                    $user->id,
                    $request->use_point,
                    "予約ID:{$reservation->id} でポイント利用"
                );
                
                $point_discount = $request->use_point;
            }
            
            // 実際の決済金額（ポイント割引後）
            $payment_amount = $total_price - $point_discount;
            
            // クレジット決済処理（割引後金額で）
            if ($request->payment == 1 && $payment_amount > 0) {
                // カード番号からスペースを削除
                $card_number = $request->card_number ? str_replace(' ', '', $request->card_number) : null;
                
                $card_data = [
                    'token' => $request->token,
                    'card_number' => $card_number,
                    'card_expire' => $request->card_expire,
                    'security_code' => $request->security_code,
                ];
                
                $this->veritrans_service->processPayment(
                    $user->id,
                    $reservation,
                    $card_data,
                    $payment_amount
                );
            }
            
            // ポイント付与（予約確定時に宿泊でポイント加算）
            // 例: 1泊あたり100ポイント（ビジネスルールに応じて調整）
            if ($reservation->status == ReservationConst::STATUS_RESERVED) {
                $point_to_add = $reservation->days * 100; // 1泊100ポイント
                if ($point_to_add > 0) {
                    $from_date = Carbon::now()->format('Y-m-d');
                    $to_date = Carbon::now()->addYear()->format('Y-m-d'); // 1年間有効
                    
                    $this->point_service->addPoint(
                        $user->id,
                        $point_to_add,
                        "予約ID:{$reservation->id} 宿泊ポイント付与",
                        $from_date,
                        $to_date
                );
                }
            }
            
            // カレンダーステータス更新
            if (isset($reservation_data['calendar_id']) && $reservation_data['calendar_id']) {
                Calendar::where('id', $reservation_data['calendar_id'])
                    ->update(['status' => ReservationConst::STATUS_UNDER_RESERVATION]);
            }
            
            // フリーデイの場合は残数減少
            if (isset($reservation_data['freeday_id'])) {
                $freeday = Freeday::findOrFail($reservation_data['freeday_id']);
                $this->freeday_service->consumeFreeday($freeday, $reservation_data['days']);
            }
            
            // 一時データ削除
            TmpOrderDetail::where('user_id', $user->id)->delete();
            session()->forget('reservation_data');
            
            // 予約ログ保存
            ReservationLog::create([
                'reservation_id' => $reservation->id,
                'user_id' => $user->id,
                'action' => 'create',
                'data' => json_encode($reservation_data),
            ]);
            
            DB::commit();
            
            // メール送信（非同期で実行、エラーはログに記録）
            try {
                // 管理者へ通知
                $admin_email = config('mail.admin_email', 'admin@soranoniwa.jp');
                Mail::to($admin_email)->send(new AdminMail($reservation, 'new'));
                
                // ユーザーへ確認メール
                Mail::to($user->email)->send(new ReservationConfirmMail($reservation));
            } catch (\Exception $e) {
                \Log::warning('Mail sending failed: ' . $e->getMessage());
                // メール送信失敗は予約処理を止めない
            }
            
            return redirect()->route('reservation.complete')
                ->with('reservation_id', $reservation->id);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Reservation Error: ' . $e->getMessage());
            return back()->withErrors(['error' => '予約に失敗しました: ' . $e->getMessage()]);
        }
    }
    
    /**
     * 予約詳細表示
     */
    public function show(Reservation $reservation)
    {
        if ($reservation->user_id != Auth::id() && $reservation->owner_id != Auth::id()) {
            abort(403);
        }
        
        $reservation->load([
            'hotel', 
            'user', 
            'orders.orderDetails.service', 
            'orders.orderDetails.serviceOption', 
            'addOrders.addOrderDetails.service',
            'addOrders.addOrderDetails.serviceOption'
        ]);
        
        return view('reservation.show', compact('reservation'));
    }
    
    /**
     * 予約編集画面
     */
    public function edit(Reservation $reservation)
    {
        if ($reservation->user_id != Auth::id() && $reservation->owner_id != Auth::id()) {
            abort(403);
        }
        
        // キャンセル済みの予約は編集不可
        if ($reservation->status == ReservationConst::STATUS_CANCEL) {
            return redirect()->route('reservation.show', $reservation)
                ->with('error', 'キャンセル済みの予約は編集できません');
        }
        
        $reservation->load(['hotel', 'user']);
        
        return view('reservation.edit', compact('reservation'));
    }
    
    /**
     * 予約更新
     */
    public function update(ReservationUpdateRequest $request, Reservation $reservation)
    {
        if ($reservation->user_id != Auth::id() && $reservation->owner_id != Auth::id()) {
            abort(403);
        }
        
        // キャンセル済みの予約は更新不可
        if ($reservation->status == ReservationConst::STATUS_CANCEL) {
            return redirect()->route('reservation.show', $reservation)
                ->with('error', 'キャンセル済みの予約は更新できません');
        }
        
        DB::transaction(function () use ($request, $reservation) {
            // 宿泊日数を計算
            $checkin_date = Carbon::parse($request->checkin_date);
            $checkout_date = Carbon::parse($request->checkout_date);
            $days = $checkin_date->diffInDays($checkout_date);
            
            // 予約情報を更新
            $reservation->update([
                'checkin_date' => $request->checkin_date,
                'checkout_date' => $request->checkout_date,
                'days' => $days,
                'adult' => $request->adult,
                'child' => $request->child ?? 0,
                'dog' => $request->dog ?? 0,
                'note' => $request->note,
            ]);
            
            // ログ保存
            ReservationLog::create([
                'reservation_id' => $reservation->id,
                'user_id' => Auth::id(),
                'action' => 'update',
                'data' => json_encode([
                    'checkin_date' => $request->checkin_date,
                    'checkout_date' => $request->checkout_date,
                    'adult' => $request->adult,
                    'child' => $request->child ?? 0,
                    'dog' => $request->dog ?? 0,
                    'updated_at' => now(),
                ]),
            ]);
        });
        
        return redirect()->route('reservation.show', $reservation)
            ->with('success', '予約情報を更新しました');
    }
    
    /**
     * 予約完了画面
     */
    public function complete()
    {
        return view('reservation.complete');
    }
    
    /**
     * 予約キャンセル
     */
    public function cancel(Reservation $reservation)
    {
        if ($reservation->user_id != Auth::id() && $reservation->owner_id != Auth::id()) {
            abort(403);
        }
        
        DB::transaction(function () use ($reservation) {
            // ステータス更新
            $reservation->update(['status' => ReservationConst::STATUS_CANCEL]);
            
            // カレンダー解放
            if ($reservation->calendar_id) {
                Calendar::where('id', $reservation->calendar_id)
                    ->update(['status' => 1]);
            }
            
            // フリーデイの場合は泊数を戻す（ビジネスルールに応じて実装）
            // ここでは実装しないが、必要に応じて追加可能
            
            // ログ保存
            ReservationLog::create([
                'reservation_id' => $reservation->id,
                'user_id' => Auth::id(),
                'action' => 'cancel',
                'data' => json_encode(['canceled_at' => now()]),
            ]);
            
            // 管理者へキャンセル通知
            try {
                $admin_email = config('mail.admin_email', 'admin@soranoniwa.jp');
                Mail::to($admin_email)->send(new AdminMail($reservation, 'cancel'));
            } catch (\Exception $e) {
                \Log::warning('Mail sending failed: ' . $e->getMessage());
            }
        });
        
        return redirect()->route('reservation.index')
            ->with('success', '予約をキャンセルしました');
    }
}
