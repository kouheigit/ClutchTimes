# Phase 7-9: ポイントシステムから管理画面まで

---

## Phase 7: ポイントシステム（1週間）

### 目標
会員ポイント機能の完全実装

### Step 7-1: PointService作成（Day 1-2）

```bash
touch app/Services/PointService.php
```

```php
<?php
// app/Services/PointService.php

namespace App\Services;

use App\Models\UserPoint;
use App\Models\UserPointLog;
use App\Models\User;
use Carbon\Carbon;
use DB;

class PointService
{
    /**
     * ポイント付与
     * 
     * @param int $userId ユーザーID
     * @param int $point ポイント数
     * @param string $reason 理由
     * @param string $from 有効開始日
     * @param string $to 有効期限
     */
    public function addPoint($userId, $point, $reason, $from, $to)
    {
        DB::transaction(function () use ($userId, $point, $reason, $from, $to) {
            // ポイント残高追加
            $userPoint = UserPoint::create([
                'user_id' => $userId,
                'point' => $point,
                'from' => $from,
                'to' => $to,
            ]);
            
            // ログ記録
            UserPointLog::create([
                'user_id' => $userId,
                'point' => $point,
                'reason' => $reason,
                'type' => 1, // 1:加算
            ]);
            
            \Log::info('Point Added', [
                'user_id' => $userId,
                'point' => $point,
                'reason' => $reason
            ]);
        });
    }
    
    /**
     * ポイント利用
     * 
     * @param int $userId ユーザーID
     * @param int $point 使用ポイント数
     * @param string $reason 理由
     */
    public function usePoint($userId, $point, $reason)
    {
        // 利用可能ポイントチェック
        $availablePoints = $this->getAvailablePoints($userId);
        
        if ($availablePoints < $point) {
            throw new \Exception("ポイントが不足しています（利用可能: {$availablePoints}P）");
        }
        
        DB::transaction(function () use ($userId, $point, $reason) {
            // 古いポイントから消費（FIFO）
            $remaining = $point;
            
            $userPoints = UserPoint::where('user_id', $userId)
                ->where('to', '>=', now()->format('Y-m-d'))
                ->where('point', '>', 0)
                ->orderBy('from', 'asc') // 古い順
                ->lockForUpdate() // 排他ロック
                ->get();
            
            foreach ($userPoints as $userPoint) {
                if ($remaining <= 0) {
                    break;
                }
                
                if ($userPoint->point >= $remaining) {
                    // このポイントで足りる
                    $userPoint->point -= $remaining;
                    $userPoint->save();
                    $remaining = 0;
                } else {
                    // このポイントを全て使っても足りない
                    $remaining -= $userPoint->point;
                    $userPoint->point = 0;
                    $userPoint->save();
                }
            }
            
            // ログ記録
            UserPointLog::create([
                'user_id' => $userId,
                'point' => $point,
                'reason' => $reason,
                'type' => 2, // 2:減算
            ]);
            
            \Log::info('Point Used', [
                'user_id' => $userId,
                'point' => $point,
                'reason' => $reason
            ]);
        });
    }
    
    /**
     * 利用可能ポイント取得
     */
    public function getAvailablePoints($userId)
    {
        $now = Carbon::now()->format('Y-m-d');
        
        return UserPoint::where('user_id', $userId)
            ->where('to', '>=', $now)
            ->where('point', '>', 0)
            ->sum('point');
    }
    
    /**
     * ポイント履歴取得
     */
    public function getPointHistory($userId, $limit = 50)
    {
        return UserPointLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * 有効期限別ポイント残高取得
     */
    public function getPointBalanceByExpiry($userId)
    {
        $now = Carbon::now()->format('Y-m-d');
        
        return UserPoint::where('user_id', $userId)
            ->where('to', '>=', $now)
            ->where('point', '>', 0)
            ->orderBy('to', 'asc')
            ->get();
    }
    
    /**
     * ポイント有効期限切れ処理（バッチ処理）
     */
    public function expirePoints()
    {
        $today = Carbon::now()->format('Y-m-d');
        
        $expiredPoints = UserPoint::where('to', '<', $today)
            ->where('point', '>', 0)
            ->get();
        
        foreach ($expiredPoints as $userPoint) {
            if ($userPoint->point > 0) {
                // ログ記録
                UserPointLog::create([
                    'user_id' => $userPoint->user_id,
                    'point' => $userPoint->point,
                    'reason' => 'ポイント有効期限切れ',
                    'type' => 3, // 3:失効
                ]);
                
                // ポイント削除
                $userPoint->point = 0;
                $userPoint->save();
            }
        }
        
        \Log::info('Points Expired', ['count' => $expiredPoints->count()]);
    }
}
```

### Step 7-2: 予約・注文へのポイント組み込み（Day 3-4）

#### ReservationControllerにポイント処理追加

```php
<?php
// ReservationController にポイント処理を追加

use App\Services\PointService;

class ReservationController extends Controller
{
    private $freeday_service;
    private $point_service;
    
    public function __construct(
        FreedayService $freeday_service,
        PointService $point_service
    ) {
        $this->freeday_service = $freeday_service;
        $this->point_service = $point_service;
    }
    
    /**
     * 確認画面（ポイント利用対応）
     */
    public function confirm(Request $request)
    {
        $user = Auth::user();
        $reservation_data = session('reservation_data');
        
        $tmp_orders = TmpOrderDetail::where('user_id', $user->id)
            ->with(['service', 'serviceOption'])
            ->get();
        
        $service_total = $tmp_orders->sum('total_price');
        
        // 利用可能ポイント取得
        $available_points = $this->point_service->getAvailablePoints($user->id);
        
        // ポイント利用可能額（例: 合計金額まで）
        $max_point_use = min($available_points, $service_total);
        
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
     * 予約登録（ポイント利用対応）
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment' => 'required|integer|in:0,1',
            'use_point' => 'nullable|integer|min:0',
        ]);
        
        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            $reservation_data = session('reservation_data');
            
            // 予約作成...（前述のコード）
            
            // サービス注文作成...
            $tmp_orders = TmpOrderDetail::where('user_id', $user->id)->get();
            $service_total = $tmp_orders->sum('total_price');
            
            // ★ ポイント利用処理 ★
            $point_discount = 0;
            if ($request->use_point && $request->use_point > 0) {
                $available_points = $this->point_service->getAvailablePoints($user->id);
                
                if ($request->use_point > $available_points) {
                    throw new \Exception('ポイントが不足しています');
                }
                
                if ($request->use_point > $service_total) {
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
            
            // 実際の決済金額
            $payment_amount = $service_total - $point_discount;
            
            // クレジット決済（割引後金額で）
            if ($request->payment == 1 && $payment_amount > 0) {
                $this->processPayment($user, $reservation, $request, $payment_amount);
            }
            
            // ポイント付与（宿泊でポイント加算）
            // 例: 1泊につき100ポイント付与
            $earned_points = $reservation_data['days'] * 100;
            $this->point_service->addPoint(
                $user->id,
                $earned_points,
                "予約ID:{$reservation->id} 宿泊特典",
                now()->addDay()->format('Y-m-d'),
                now()->addYear()->format('Y-m-d')
            );
            
            // その他の処理...
            
            DB::commit();
            
            return redirect()->route('reservation.complete')
                ->with('reservation_id', $reservation->id)
                ->with('earned_points', $earned_points);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Reservation Error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
```

### Step 7-3: ポイント表示機能（Day 5）

#### MypageControllerにポイント表示追加

```php
<?php
// app/Http/Controllers/MypageController.php

use App\Services\PointService;

class MypageController extends Controller
{
    private $point_service;
    
    public function __construct(PointService $point_service)
    {
        $this->point_service = $point_service;
    }
    
    /**
     * ポイント履歴表示
     */
    public function pointlog()
    {
        $user = Auth::user();
        
        // ポイント残高（有効期限別）
        $point_balances = $this->point_service->getPointBalanceByExpiry($user->id);
        
        // ポイント履歴
        $point_logs = $this->point_service->getPointHistory($user->id, 100);
        
        // 利用可能合計ポイント
        $total_available_points = $this->point_service->getAvailablePoints($user->id);
        
        return view('mypage.pointlog', compact(
            'point_balances',
            'point_logs',
            'total_available_points'
        ));
    }
}
```

---

## Phase 8: 招待機能（3-5日）

### Step 8-1: Invitationモデル・コントローラー（Day 1-2）

```bash
php artisan make:controller Invitation/RegisterController
```

```php
<?php
// app/Http/Controllers/Invitation/RegisterController.php

namespace App\Http\Controllers\Invitation;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * 招待URL表示
     */
    public function show($token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('status', 1)
            ->firstOrFail();
        
        // 招待元の予約情報
        $reservation = Reservation::findOrFail($invitation->reservation_id);
        
        return view('invitation.register', compact('invitation', 'reservation'));
    }
    
    /**
     * ゲストユーザー登録
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|exists:invitations,token',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'tel' => 'required|string',
        ]);
        
        $invitation = Invitation::where('token', $request->token)->firstOrFail();
        
        DB::beginTransaction();
        
        try {
            // ゲストユーザー作成
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'tel' => $request->tel,
                'type' => 1, // 一般ユーザー
                'user_id' => $invitation->owner_id, // オーナーと紐付け
                'status' => 1,
            ]);
            
            // 招待情報更新
            $invitation->update([
                'user_id' => $user->id,
                'status' => 2, // 登録済み
            ]);
            
            // 予約にゲストを紐付け
            $reservation = Reservation::findOrFail($invitation->reservation_id);
            $reservation->update([
                'user_id' => $user->id,
                'invitation_id' => $invitation->id,
            ]);
            
            DB::commit();
            
            // 自動ログイン
            Auth::login($user);
            
            return redirect()->route('invitation.complete');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Invitation Error: ' . $e->getMessage());
            return back()->withErrors(['error' => '登録に失敗しました']);
        }
    }
}
```

### Step 8-2: InvitationMail作成（Day 3）

```bash
php artisan make:mail InvitationMail
```

```php
<?php
// app/Mail/InvitationMail.php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\Invitation;

class InvitationMail extends Mailable
{
    public $invitation;
    public $reservation;
    
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
        $this->reservation = $invitation->reservation;
    }
    
    public function build()
    {
        return $this->subject('【空ノ庭】ご招待のお知らせ')
            ->view('emails.invitation')
            ->with([
                'name' => $this->invitation->name,
                'url' => route('invitation.register', ['token' => $this->invitation->token]),
                'checkin_date' => $this->reservation->checkin_date,
                'checkout_date' => $this->reservation->checkout_date,
                'hotel_name' => $this->reservation->hotel->name,
            ]);
    }
}
```

```blade
{{-- resources/views/emails/invitation.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h2>{{ $name }} 様</h2>
    
    <p>空ノ庭へのご招待メールです。</p>
    
    <h3>ご予約情報</h3>
    <ul>
        <li>施設: {{ $hotel_name }}</li>
        <li>チェックイン: {{ $checkin_date }}</li>
        <li>チェックアウト: {{ $checkout_date }}</li>
    </ul>
    
    <p>以下のURLから会員登録を行ってください。</p>
    
    <p><a href="{{ $url }}">{{ $url }}</a></p>
    
    <p>ご不明な点がございましたら、お気軽にお問い合わせください。</p>
    
    <hr>
    <p>
        GLAMDAY STYLE TEITAKU 空ノ庭<br>
        Email: info@soranoniwa.jp
    </p>
</body>
</html>
```

---

## Phase 9: 管理画面実装（2週間）

### 目標
Laravel Adminで全リソースの管理画面を構築

### Step 9-1: Laravel Admin基本設定（Day 1）

```bash
# Laravel Admin インストール（Phase 0で実施済み）
php artisan admin:install

# 管理者作成
# Username: admin
# Password: admin
# http://localhost:8081/admin でアクセス
```

### Step 9-2: ルート設定（Day 1）

```php
<?php
// app/Admin/routes.php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    // ユーザー管理
    $router->resource('users', UserController::class);
    
    // ホテル管理
    $router->resource('hotels', HotelController::class);
    
    // サービス管理
    $router->resource('services', ServiceController::class);
    $router->resource('service_options', ServiceOptionController::class);
    
    // カレンダー管理
    $router->resource('calendars', CalendarController::class);
    $router->resource('calendar_options', CalendarOptionController::class);
    $router->resource('freedays', FreedayController::class);
    
    // 予約管理
    $router->resource('reservations', ReservationController::class);
    $router->resource('orders', OrderController::class);
    $router->resource('order_details', OrderDetailController::class);
    
    // 招待管理
    $router->resource('invitations', InvitationController::class);
    
    // 休日管理
    $router->resource('holidays', HolidayController::class);
    
    // お知らせ管理
    $router->resource('news', NewsController::class);
    $router->resource('information', InformationController::class);
    
    // ログ管理
    $router->resource('veritrans_logs', VeritransLogController::class);
});
```

### Step 9-3: 管理画面コントローラー作成（Day 2-7）

#### ユーザー管理画面
```bash
php artisan admin:make UserController --model=App\\Models\\User
```

```php
<?php
// app/Admin/Controllers/UserController.php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Consts\UserConst;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    protected $title = 'ユーザー管理';

    protected function grid()
    {
        $grid = new Grid(new User());
        
        $grid->column('id', 'ID')->sortable();
        $grid->column('member_id', '会員ID');
        $grid->column('name', '氏名');
        $grid->column('email', 'メール');
        $grid->column('tel', '電話番号');
        $grid->column('type', 'タイプ')->using(UserConst::TYPE_LIST)->label([
            UserConst::TYPE_GENERAL => 'info',
            UserConst::TYPE_OWNER => 'success',
        ]);
        $grid->column('status', 'ステータス')->using([
            0 => '無効',
            1 => '有効',
        ])->dot([
            0 => 'danger',
            1 => 'success',
        ]);
        $grid->column('created_at', '登録日')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        // フィルター
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('member_id', '会員ID');
            $filter->like('name', '氏名');
            $filter->like('email', 'メール');
            $filter->equal('type', 'タイプ')->select(UserConst::TYPE_LIST);
            $filter->equal('status', 'ステータス')->select([0 => '無効', 1 => '有効']);
            $filter->between('created_at', '登録日')->datetime();
        });
        
        // アクション
        $grid->actions(function ($actions) {
            // 削除ボタン非表示（ソフトデリートのみ）
            $actions->disableDelete();
        });
        
        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));
        
        $show->field('id', 'ID');
        $show->field('member_id', '会員ID');
        $show->field('name', '氏名');
        $show->field('email', 'メール');
        $show->field('last_name', '姓');
        $show->field('first_name', '名');
        $show->field('last_kana', '姓（カナ）');
        $show->field('first_kana', '名（カナ）');
        $show->field('zip1', '郵便番号1');
        $show->field('zip2', '郵便番号2');
        $show->field('address1', '住所1');
        $show->field('address2', '住所2');
        $show->field('tel', '電話番号');
        $show->field('type', 'タイプ')->using(UserConst::TYPE_LIST);
        $show->field('status', 'ステータス')->using([0 => '無効', 1 => '有効']);
        $show->field('created_at', '登録日時');
        $show->field('updated_at', '更新日時');
        
        // リレーション
        $show->hotels('所属ホテル', function ($hotels) {
            $hotels->setResource('/admin/hotels');
            $hotels->id();
            $hotels->name();
        });
        
        $show->reservations('予約履歴', function ($reservations) {
            $reservations->setResource('/admin/reservations');
            $reservations->id();
            $reservations->checkin_date();
            $reservations->checkout_date();
            $reservations->status();
        });
        
        return $show;
    }

    protected function form()
    {
        $form = new Form(new User());
        
        $form->text('member_id', '会員ID');
        $form->text('name', '氏名')->required();
        $form->email('email', 'メール')->required();
        $form->password('password', 'パスワード');
        
        $form->divider('個人情報');
        $form->text('last_name', '姓');
        $form->text('first_name', '名');
        $form->text('last_kana', '姓（カナ）');
        $form->text('first_kana', '名（カナ）');
        $form->text('zip1', '郵便番号1')->placeholder('123');
        $form->text('zip2', '郵便番号2')->placeholder('4567');
        $form->text('address1', '住所1（都道府県・市区町村）');
        $form->text('address2', '住所2（番地・建物名）');
        $form->text('tel', '電話番号');
        
        $form->divider('システム設定');
        $form->select('type', 'ユーザータイプ')->options(UserConst::TYPE_LIST)->default(1);
        $form->switch('status', 'ステータス')->default(1);
        $form->multipleSelect('hotels', 'ホテル')->options(\App\Models\Hotel::all()->pluck('name', 'id'));
        
        // 保存前処理
        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });
        
        return $form;
    }
}
```

#### 予約管理画面（重要）
```bash
php artisan admin:make ReservationController --model=App\\Models\\Reservation
```

```php
<?php
// app/Admin/Controllers/ReservationController.php

namespace App\Admin\Controllers;

use App\Models\Reservation;
use App\Consts\ReservationConst;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ReservationController extends AdminController
{
    protected $title = '予約管理';

    protected function grid()
    {
        $grid = new Grid(new Reservation());
        
        // カラム設定
        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', 'ユーザー');
        $grid->column('hotel.name', 'ホテル');
        $grid->column('checkin_date', 'チェックイン')->display(function ($date) {
            return date('Y/m/d', strtotime($date));
        });
        $grid->column('checkout_date', 'チェックアウト')->display(function ($date) {
            return date('Y/m/d', strtotime($date));
        });
        $grid->column('days', '泊数')->sortable();
        $grid->column('adult', '大人')->sortable();
        $grid->column('child', '子供');
        $grid->column('dog', '犬');
        $grid->column('payment', '決済')->using([
            0 => '現地払い',
            1 => 'クレジット',
        ])->label([
            0 => 'warning',
            1 => 'success',
        ]);
        $grid->column('status', 'ステータス')->using(ReservationConst::STATUS_LIST)->label([
            1 => 'info',
            2 => 'warning',
            3 => 'success',
            4 => 'primary',
            5 => 'default',
            8 => 'warning',
            9 => 'danger',
        ]);
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y/m/d H:i', strtotime($created_at));
        });
        
        // デフォルトソート
        $grid->model()->orderBy('checkin_date', 'desc');
        
        // フィルター
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            
            // ユーザー検索
            $filter->like('user.name', 'ユーザー名');
            $filter->like('user.email', 'メールアドレス');
            
            // 日付検索
            $filter->between('checkin_date', 'チェックイン日')->date();
            $filter->between('checkout_date', 'チェックアウト日')->date();
            
            // ステータス検索
            $filter->equal('status', 'ステータス')->select(ReservationConst::STATUS_LIST);
            
            // ホテル検索
            $filter->equal('hotel_id', 'ホテル')->select(\App\Models\Hotel::all()->pluck('name', 'id'));
            
            // 決済方法
            $filter->equal('payment', '決済方法')->select([
                0 => '現地払い',
                1 => 'クレジット',
            ]);
        });
        
        // エクスポート
        $grid->exporter(new ReservationExporter());
        
        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Reservation::findOrFail($id));
        
        $show->field('id', 'ID');
        $show->field('user.name', 'ユーザー');
        $show->field('owner.name', 'オーナー');
        $show->field('hotel.name', 'ホテル');
        $show->field('checkin_date', 'チェックイン日');
        $show->field('checkout_date', 'チェックアウト日');
        $show->field('checkin_time', 'チェックイン時刻');
        $show->field('checkout_time', 'チェックアウト時刻');
        $show->field('days', '宿泊日数');
        $show->field('name', '代表者名');
        $show->field('adult', '大人人数');
        $show->field('child', '子供人数');
        $show->field('dog', '犬頭数');
        $show->field('note', '備考');
        $show->field('room_key', '入室番号');
        $show->field('payment', '決済方法')->using([0 => '現地払い', 1 => 'クレジット']);
        $show->field('status', 'ステータス')->using(ReservationConst::STATUS_LIST);
        $show->field('created_at', '作成日時');
        $show->field('updated_at', '更新日時');
        
        // 関連サービス注文
        $show->orders('サービス注文', function ($orders) {
            $orders->setResource('/admin/orders');
            $orders->id();
            $orders->column('service.title', 'サービス');
            $orders->quantity();
            $orders->total_price();
            $orders->payment_status();
        });
        
        return $show;
    }

    protected function form()
    {
        $form = new Form(new Reservation());
        
        // 基本情報
        $form->select('user_id', 'ユーザー')
            ->options(\App\Models\User::all()->pluck('name', 'id'))
            ->required();
        $form->select('hotel_id', 'ホテル')
            ->options(\App\Models\Hotel::all()->pluck('name', 'id'))
            ->required();
        
        // 日程
        $form->date('checkin_date', 'チェックイン日')->required();
        $form->date('checkout_date', 'チェックアウト日')->required();
        $form->time('checkin_time', 'チェックイン時刻');
        $form->time('checkout_time', 'チェックアウト時刻');
        $form->number('days', '宿泊日数')->default(1);
        
        // ゲスト情報
        $form->text('name', '代表者名');
        $form->number('adult', '大人人数')->default(0);
        $form->number('child', '子供人数')->default(0);
        $form->number('dog', '犬頭数')->default(0);
        $form->textarea('note', '備考');
        
        // 施設情報
        $form->text('room_key', '入室番号');
        
        // 決済・ステータス
        $form->select('payment', '決済方法')->options([
            0 => '現地払い',
            1 => 'クレジット',
        ])->default(0);
        $form->select('status', 'ステータス')
            ->options(ReservationConst::STATUS_LIST)
            ->default(1);
        
        return $form;
    }
}
```

### Step 9-4: その他管理画面（Day 8-10）

同様に以下を作成：
- ServiceController（サービス管理）
- OrderController（注文管理）
- CalendarController（カレンダー管理）
- NewsController（お知らせ管理）

**作成コマンド**:
```bash
php artisan admin:make ServiceController --model=App\\Models\\Service
php artisan admin:make OrderController --model=App\\Models\\Order
php artisan admin:make CalendarController --model=App\\Models\\Calendar
php artisan admin:make NewsController --model=App\\Models\\News
```

### チェックポイント
- [ ] /admin でログイン可能
- [ ] 全リソースの一覧表示
- [ ] CRUD操作可能
- [ ] 検索・フィルター動作
- [ ] リレーションデータ表示

---

次のPhase 10-12のファイルも作成しますか？

