# Phase 4-6: 予約システムから決済連携まで

---

## Phase 4: 予約システムコア実装（2週間）

### 目標
予約機能の完全実装

### Step 4-1: ReservationControllerの作成（Day 1）

```bash
php artisan make:controller ReservationController
```

```php
<?php
// app/Http/Controllers/ReservationController.php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Calendar;
use App\Models\Service;
use App\Models\TmpOrderDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Consts\ReservationConst;
use App\Services\FreedayService;
use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;

class ReservationController extends Controller
{
    private $freeday_service;
    
    public function __construct(FreedayService $freeday_service)
    {
        $this->freeday_service = $freeday_service;
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
        
        // 予約を取得
        $reservations = Reservation::where('owner_id', $user->id)
            ->whereIn('status', [
                ReservationConst::STATUS_APPLYING,
                ReservationConst::STATUS_UNDER_RESERVATION,
                ReservationConst::STATUS_RESERVED
            ])
            ->orderBy('checkin_date', 'asc')
            ->get();
        
        return view('reservation.index', compact('calendars', 'freedays', 'reservations'));
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
            $calendar = Calendar::findOrFail($calendar_id);
            
            return view('reservation.create', compact('calendar'));
        }
        
        if ($fr) {
            // FREEDAY予約
            $freeday = Freeday::findOrFail($fr);
            
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
        
        return redirect()->route('reservation.cart');
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
        
        return redirect()->route('reservation.cart');
    }
    
    /**
     * 予約確認画面
     */
    public function confirm(Request $request)
    {
        $user = Auth::user();
        $reservation_data = session('reservation_data');
        
        if (!$reservation_data) {
            return redirect()->route('reservation.index');
        }
        
        $tmp_orders = TmpOrderDetail::where('user_id', $user->id)
            ->with(['service', 'serviceOption'])
            ->get();
        
        $service_total = $tmp_orders->sum('total_price');
        $total_price = $service_total;
        
        return view('reservation.confirm', compact(
            'reservation_data',
            'tmp_orders',
            'service_total',
            'total_price'
        ));
    }
    
    /**
     * 予約登録（決済なし版）
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            $reservation_data = session('reservation_data');
            
            if (!$reservation_data) {
                throw new \Exception('予約情報がありません');
            }
            
            // 予約作成
            $reservation = Reservation::create([
                'hotel_id' => $reservation_data['hotel_id'],
                'user_id' => $user->id,
                'owner_id' => $user->type == 2 ? $user->id : $user->user_id,
                'calendar_id' => $reservation_data['calendar_id'] ?? null,
                'checkin_date' => $reservation_data['checkin_date'],
                'checkout_date' => $reservation_data['checkout_date'],
                'days' => $reservation_data['days'],
                'adult' => $reservation_data['adult'],
                'child' => $reservation_data['child'] ?? 0,
                'dog' => $reservation_data['dog'] ?? 0,
                'note' => $request->note,
                'payment' => $request->payment ?? 0,
                'status' => ReservationConst::STATUS_UNDER_RESERVATION,
            ]);
            
            // サービス注文作成
            $tmp_orders = TmpOrderDetail::where('user_id', $user->id)->get();
            
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
            }
            
            // カレンダーステータス更新
            if ($reservation_data['calendar_id']) {
                Calendar::where('id', $reservation_data['calendar_id'])
                    ->update(['status' => ReservationConst::STATUS_UNDER_RESERVATION]);
            }
            
            // フリーデイの場合は残数減少
            if (isset($reservation_data['freeday_id'])) {
                $freeday = Freeday::findOrFail($reservation_data['freeday_id']);
                $freeday->decrement('freedays', $reservation_data['days']);
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
            
            return redirect()->route('reservation.complete')
                ->with('reservation_id', $reservation->id);
            
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
        
        $reservation->load(['hotel', 'orders.orderDetails.service', 'addOrders']);
        
        return view('reservation.show', compact('reservation'));
    }
    
    /**
     * 予約キャンセル
     */
    public function cancel(Reservation $reservation)
    {
        if ($reservation->user_id != Auth::id()) {
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
            
            // フリーデイの場合は泊数を戻す
            // （ビジネスルールに応じて実装）
            
            // ログ保存
            ReservationLog::create([
                'reservation_id' => $reservation->id,
                'user_id' => Auth::id(),
                'action' => 'cancel',
                'data' => json_encode(['canceled_at' => now()]),
            ]);
        });
        
        return redirect()->route('mypage.index')
            ->with('success', '予約をキャンセルしました');
    }
}
```

### Step 4-2: FreedayService実装（Day 2）

```bash
mkdir -p app/Services
touch app/Services/FreedayService.php
```

```php
<?php
// app/Services/FreedayService.php

namespace App\Services;

use App\Models\Freeday;
use App\Models\User;
use Carbon\Carbon;

class FreedayService
{
    /**
     * ユーザーの有効なフリーデイを取得
     */
    public function getFreedays(User $user)
    {
        $now = Carbon::now();
        
        return Freeday::where('user_id', $user->id)
            ->where('end_date', '>=', $now->format('Y-m-d'))
            ->where('freedays', '>', 0)
            ->where('status', 1)
            ->orderBy('end_date', 'asc')
            ->get();
    }
    
    /**
     * 今年度の最大フリーデイ泊数を取得
     */
    public function getYearMaxFreedaysNum(User $user)
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        
        return Freeday::where('user_id', $user->id)
            ->whereBetween('start_date', [$startOfYear, $endOfYear])
            ->sum('freedays');
    }
    
    /**
     * フリーデイ利用可能チェック
     */
    public function canUseFreeday(Freeday $freeday, $days)
    {
        // 残り泊数チェック
        if ($freeday->freedays < $days) {
            return false;
        }
        
        // 有効期限チェック
        if (Carbon::parse($freeday->end_date)->isPast()) {
            return false;
        }
        
        // 利用開始日チェック
        $availableFrom = Carbon::parse($freeday->start_date)->firstOfMonth()->subMonths(18);
        if (Carbon::now()->isBefore($availableFrom)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * フリーデイ消費
     */
    public function consumeFreeday(Freeday $freeday, $days)
    {
        if (!$this->canUseFreeday($freeday, $days)) {
            throw new \Exception('フリーデイが利用できません');
        }
        
        $freeday->decrement('freedays', $days);
        
        return $freeday;
    }
    
    /**
     * フリーデイ返却（キャンセル時）
     */
    public function returnFreeday(Freeday $freeday, $days)
    {
        $freeday->increment('freedays', $days);
        
        return $freeday;
    }
}
```

### Step 4-3: CalendarController実装（Day 3）

```bash
php artisan make:controller CalendarController
```

```php
<?php
// app/Http/Controllers/CalendarController.php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Reservation;
use App\Models\Holiday;
use App\Consts\ReservationConst;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * カレンダー表示
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 年月指定（デフォルトは今月）
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        
        $date = Carbon::createFromDate($year, $month, 1);
        
        // 該当月のカレンダー取得
        $calendars = Calendar::where('user_id', $user->id)
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->with(['hotel'])
            ->orderBy('start_date', 'asc')
            ->get();
        
        // 休日取得
        $holidays = Holiday::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->pluck('date')
            ->map(function($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();
        
        // 予約済み日程
        $reservations = Reservation::where('user_id', $user->id)
            ->where(function($query) use ($date) {
                $query->whereYear('checkin_date', $date->year)
                      ->whereMonth('checkin_date', $date->month);
            })
            ->orWhere(function($query) use ($date) {
                $query->whereYear('checkout_date', $date->year)
                      ->whereMonth('checkout_date', $date->month);
            })
            ->get();
        
        // 前月・次月
        $prevMonth = $date->copy()->subMonth();
        $nextMonth = $date->copy()->addMonth();
        
        return view('calendar.index', compact(
            'calendars',
            'holidays',
            'reservations',
            'year',
            'month',
            'prevMonth',
            'nextMonth'
        ));
    }
    
    /**
     * 日付詳細
     */
    public function detail($year, $month, $day)
    {
        $user = Auth::user();
        $date = Carbon::createFromDate($year, $month, $day);
        
        // その日のカレンダー情報取得
        $calendar = Calendar::where('user_id', $user->id)
            ->where('date', $date->format('Y-m-d'))
            ->orWhere(function($query) use ($date) {
                $query->where('start_date', '<=', $date->format('Y-m-d'))
                      ->where('end_date', '>=', $date->format('Y-m-d'));
            })
            ->first();
        
        // その日の予約
        $reservation = Reservation::where('user_id', $user->id)
            ->where('checkin_date', '<=', $date->format('Y-m-d'))
            ->where('checkout_date', '>=', $date->format('Y-m-d'))
            ->first();
        
        return view('calendar.detail', compact('calendar', 'reservation', 'date'));
    }
}
```

---

## Phase 5: サービス注文・カート機能（1週間）

### Step 5-1: ServiceController実装（Day 1-2）

```bash
php artisan make:controller ServiceController
```

```php
<?php
// app/Http/Controllers/ServiceController.php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceOption;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Auth;

class ServiceController extends Controller
{
    /**
     * サービス一覧
     */
    public function index()
    {
        // 現地注文可能なサービス
        $services = Service::where('status', 1)
            ->where('tab', 2) // 現地注文タブ
            ->orderBy('sort', 'asc')
            ->with('serviceOptions')
            ->get();
        
        // 最新の予約取得
        $last_reservation = Reservation::getLastReservation();
        
        return view('services.index', compact('services', 'last_reservation'));
    }
    
    /**
     * サービス詳細・注文画面
     */
    public function show(Service $service, Request $request)
    {
        $service->load('serviceOptions');
        
        // 予約情報（任意）
        $reservation_id = $request->input('reservation_id');
        $reservation = null;
        
        if ($reservation_id) {
            $reservation = Reservation::findOrFail($reservation_id);
            
            if ($reservation->user_id != Auth::id()) {
                abort(403);
            }
        }
        
        return view('services.show', compact('service', 'reservation'));
    }
    
    /**
     * カート追加
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'service_option_id' => 'nullable|exists:service_options,id',
            'quantity' => 'required|integer|min:1',
            'reservation_id' => 'nullable|exists:reservations,id',
        ]);
        
        $service = Service::findOrFail($request->service_id);
        $user = Auth::user();
        
        // 最小注文数チェック
        if ($request->quantity < $service->minimum) {
            return back()->withErrors([
                'quantity' => "最小注文数は{$service->minimum}{$service->unit}です"
            ]);
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
        
        // カート取得または作成
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        
        // カート明細追加
        CartDetail::create([
            'cart_id' => $cart->id,
            'service_id' => $service->id,
            'service_option_id' => $request->service_option_id,
            'price' => $price,
            'quantity' => $request->quantity,
            'total_price' => $price * $request->quantity,
        ]);
        
        return redirect()->route('cart.index')
            ->with('success', 'カートに追加しました');
    }
}
```

### Step 5-2: CartController実装（Day 3-4）

```bash
php artisan make:controller CartController
```

```php
<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Auth;
use DB;

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
        
        if (!$cart) {
            return view('cart.index', ['cart' => null, 'total_price' => 0]);
        }
        
        $total_price = $cart->cartDetails->sum('total_price');
        
        // 最新予約
        $last_reservation = Reservation::getLastReservation();
        
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
        
        $last_reservation = Reservation::getLastReservation();
        
        return view('cart.confirm', compact('cart', 'total_price', 'last_reservation'));
    }
    
    /**
     * 注文確定（決済なし版）
     */
    public function store(Cart $cart, Request $request)
    {
        if ($cart->user_id != Auth::id()) {
            abort(403);
        }
        
        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            
            // 各カート明細を注文に変換
            foreach ($cart->cartDetails as $detail) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'reservation_id' => $request->reservation_id,
                    'service_id' => $detail->service_id,
                    'price' => $detail->price,
                    'quantity' => $detail->quantity,
                    'total_price' => $detail->total_price,
                    'payment' => $request->payment ?? 0,
                    'type' => 1,
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
            }
            
            // カート削除
            $cart->cartDetails()->delete();
            $cart->delete();
            
            DB::commit();
            
            return redirect()->route('cart.complete')
                ->with('success', '注文が完了しました');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Cart Order Error: ' . $e->getMessage());
            return back()->withErrors(['error' => '注文に失敗しました']);
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
```

---

## Phase 6: 決済連携（Veritrans）（1-2週間）⭐⭐重要

### 目標
クレジットカード決済機能の完全実装

### Step 6-1: Veritrans SDK配置（Day 1）

#### 1. local_packagesディレクトリ作成
```bash
mkdir -p src/local_packages/veritrans-tgmdk
```

#### 2. composer.json修正
```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./local_packages/veritrans-tgmdk",
            "options": {
                "symlink": false
            }
        }
    ],
    "require": {
        "veritrans/tgmdk": "^1.1.8"
    }
}
```

#### 3. 3GPSMDK.properties設定
```properties
# src/local_packages/veritrans-tgmdk/src/tgMdk/3GPSMDK.properties

# テスト環境設定
MERCHANT_ID=your_merchant_id
MERCHANT_PASS=your_merchant_pass
MERCHANT_CCID=your_merchant_ccid
MERCHANT_SECRET_KEY=your_secret_key

# 接続先URL
CONNECTION_URL=https://test-gateway.veritrans.co.jp/4gw
CONNECTION_TIMEOUT=60000
```

### Step 6-2: 決済処理の実装（Day 2-5）

#### ReservationControllerに決済処理追加

```php
<?php
// app/Http/Controllers/ReservationController.php

use tgMdk\TGMDK_Config;
use tgMdk\TGMDK_Transaction;
use tgMdk\dto\CardAuthorizeRequestDto;
use tgMdk\dto\CardAuthorizeResponseDto;
use App\Models\VeritransLog;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    /**
     * トランザクションステータスコード
     */
    public const TXN_FAILURE_CODE = 'failure';
    public const TXN_PENDING_CODE = 'pending';
    public const TXN_SUCCESS_CODE = 'success';
    
    /**
     * 予約登録（決済対応版）
     */
    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'payment' => 'required|integer|in:0,1',
            'card_number' => 'required_if:payment,1|string',
            'card_expire' => 'required_if:payment,1|string',
            'security_code' => 'required_if:payment,1|string',
            'token' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            $reservation_data = session('reservation_data');
            
            if (!$reservation_data) {
                throw new \Exception('予約情報がありません');
            }
            
            // 予約作成
            $reservation = Reservation::create([
                'hotel_id' => $reservation_data['hotel_id'],
                'user_id' => $user->id,
                'owner_id' => $user->type == 2 ? $user->id : $user->user_id,
                'calendar_id' => $reservation_data['calendar_id'] ?? null,
                'checkin_date' => $reservation_data['checkin_date'],
                'checkout_date' => $reservation_data['checkout_date'],
                'days' => $reservation_data['days'],
                'adult' => $reservation_data['adult'],
                'child' => $reservation_data['child'] ?? 0,
                'dog' => $reservation_data['dog'] ?? 0,
                'note' => $request->note,
                'payment' => $request->payment,
                'status' => ReservationConst::STATUS_UNDER_RESERVATION,
            ]);
            
            // サービス注文作成
            $tmp_orders = TmpOrderDetail::where('user_id', $user->id)->get();
            $total_price = 0;
            
            foreach ($tmp_orders as $tmp) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'reservation_id' => $reservation->id,
                    'service_id' => $tmp->service_id,
                    'price' => $tmp->price,
                    'quantity' => $tmp->quantity,
                    'total_price' => $tmp->total_price,
                    'payment' => $request->payment,
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
            
            // ★ クレジット決済処理 ★
            if ($request->payment == 1 && $total_price > 0) {
                $this->processPayment($user, $reservation, $request, $total_price);
            }
            
            // カレンダーステータス更新
            if ($reservation_data['calendar_id']) {
                Calendar::where('id', $reservation_data['calendar_id'])
                    ->update(['status' => ReservationConst::STATUS_UNDER_RESERVATION]);
            }
            
            // 一時データ削除
            TmpOrderDetail::where('user_id', $user->id)->delete();
            session()->forget('reservation_data');
            
            DB::commit();
            
            return redirect()->route('reservation.complete')
                ->with('reservation_id', $reservation->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Reservation Error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Veritrans決済処理
     */
    private function processPayment($user, $reservation, $request, $total_price)
    {
        // Veritrans設定読み込み
        // 開発環境
        TGMDK_Config::getInstance("/data/local_packages/veritrans-tgmdk/src/tgMdk/3GPSMDK.properties");
        // 本番環境（コメントアウト）
        // TGMDK_Config::getInstance("/home/xxx/vendor/veritrans/tgmdk/src/tgMdk/3GPSMDK.properties");
        
        // トランザクション作成
        $transaction = new TGMDK_Transaction();
        $request_data = new CardAuthorizeRequestDto();
        
        // 注文ID生成（ユニーク）
        $orderId = $user->id . '-' . $reservation->id . '-' . date("YmdHis");
        
        // リクエストデータ設定
        $request_data->setOrderId($orderId);
        $request_data->setAmount($total_price);
        
        // カード情報設定
        if ($request->token) {
            // トークン決済（推奨）
            $request_data->setToken($request->token);
        } else {
            // 直接カード情報（非推奨だがテスト用）
            $request_data->setCardNumber($request->card_number);
            $request_data->setCardExpire($request->card_expire);
            $request_data->setSecurityCode($request->security_code);
        }
        
        // API実行
        $response_data = $transaction->execute($request_data);
        
        if ($response_data instanceof CardAuthorizeResponseDto) {
            // 結果取得
            $txn_status = $response_data->getMStatus();
            $txn_result_code = $response_data->getVResultCode();
            $error_message = $response_data->getMerrMsg();
            
            // ログ保存（必須）
            VeritransLog::create([
                'user_id' => $user->id,
                'reservation_id' => $reservation->id,
                'order_id' => $orderId,
                'type' => 1,
                'txn_status' => $txn_status,
                'txn_result_code' => $txn_result_code,
                'err_message' => $error_message,
            ]);
            
            // 結果判定
            if (self::TXN_SUCCESS_CODE === $txn_status) {
                // 成功
                $center_reference_number = $response_data->getCenterReferenceNumber();
                
                // 予約・注文のpayment_statusを更新
                $reservation->update(['status' => ReservationConst::STATUS_RESERVED]);
                
                Order::where('reservation_id', $reservation->id)
                    ->update(['payment_status' => 1]);
                
                \Log::info('Payment Success', [
                    'orderId' => $orderId,
                    'center_reference_number' => $center_reference_number
                ]);
                
            } else if (self::TXN_PENDING_CODE === $txn_status) {
                // ペンディング
                throw ValidationException::withMessages([
                    'card_error' => 'カード決済が保留中です。しばらくお待ちください。'
                ]);
                
            } else {
                // 失敗
                throw ValidationException::withMessages([
                    'card_error' => "カード決済でエラーが発生しました: {$error_message}"
                ]);
            }
        } else {
            throw new \Exception('決済処理でエラーが発生しました');
        }
    }
}
```

### Step 6-3: エラーハンドリング強化（Day 6）

```php
/**
 * 決済処理（エラーハンドリング強化版）
 */
private function processPayment($user, $reservation, $request, $total_price)
{
    try {
        // Veritrans設定
        TGMDK_Config::getInstance("/data/local_packages/veritrans-tgmdk/src/tgMdk/3GPSMDK.properties");
        
        $transaction = new TGMDK_Transaction();
        $request_data = new CardAuthorizeRequestDto();
        
        $orderId = $user->id . '-' . $reservation->id . '-' . date("YmdHis");
        
        $request_data->setOrderId($orderId);
        $request_data->setAmount($total_price);
        $request_data->setToken($request->token);
        
        // タイムアウト対策
        $response_data = $transaction->execute($request_data);
        
        if (!$response_data) {
            throw new \Exception('決済APIからレスポンスがありません');
        }
        
        if ($response_data instanceof CardAuthorizeResponseDto) {
            $txn_status = $response_data->getMStatus();
            $txn_result_code = $response_data->getVResultCode();
            $error_message = $response_data->getMerrMsg();
            
            // ログ保存
            $log = VeritransLog::create([
                'user_id' => $user->id,
                'reservation_id' => $reservation->id,
                'order_id' => $orderId,
                'type' => 1,
                'txn_status' => $txn_status,
                'txn_result_code' => $txn_result_code,
                'err_message' => $error_message,
            ]);
            
            // 成功判定
            if (self::TXN_SUCCESS_CODE === $txn_status) {
                return true;
            } else {
                throw ValidationException::withMessages([
                    'card_error' => "決済エラー[{$txn_result_code}]: {$error_message}"
                ]);
            }
        } else {
            throw new \Exception('不正なレスポンス形式');
        }
        
    } catch (ValidationException $e) {
        // バリデーションエラーはそのまま投げる
        throw $e;
    } catch (\Exception $e) {
        // その他のエラーはログに記録
        \Log::error('Payment Exception', [
            'user_id' => $user->id,
            'reservation_id' => $reservation->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        throw new \Exception('決済処理中にエラーが発生しました。管理者にお問い合わせください。');
    }
}
```

### チェックポイント
- [ ] 予約作成からサービス選択まで完了
- [ ] カート機能が動作
- [ ] クレジット決済（テスト）成功
- [ ] 決済ログが正しく保存される
- [ ] エラー時の処理が適切

---

次のPhase 7-9のファイルも作成しますか？

