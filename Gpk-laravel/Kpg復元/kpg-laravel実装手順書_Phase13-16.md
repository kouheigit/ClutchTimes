# Phase 13-16: API連携からデプロイまで

---

## Phase 13: 外部API連携（3-5日）※オプション

### 目標
OpenWeatherMapとGoogle Maps APIの統合

### Step 13-1: WeatherService作成（Day 1）

```bash
touch app/Services/WeatherService.php
```

```php
<?php
// app/Services/WeatherService.php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    private $api_key;
    private $base_url = 'https://api.openweathermap.org/data/2.5';
    
    public function __construct()
    {
        $this->api_key = env('OPENWEATHER_API_KEY', '0176e5633457466d0d5f491966c41fb8');
    }
    
    /**
     * 今日の天気を取得（キャッシュ30分）
     */
    public function getTodayWeather($city = 'Karuizawa,jp')
    {
        return Cache::remember('weather_today_' . $city, 1800, function () use ($city) {
            try {
                $client = new Client();
                $url = "{$this->base_url}/weather?q={$city}&appid={$this->api_key}&lang=ja";
                
                $response = $client->request('GET', $url);
                $data = json_decode($response->getBody(), true);
                
                return [
                    'temp' => round($data['main']['temp'] - 273.15), // ケルビン→摂氏
                    'temp_max' => round($data['main']['temp_max'] - 273.15),
                    'temp_min' => round($data['main']['temp_min'] - 273.15),
                    'weather' => $this->translateWeather($data['weather'][0]['main']),
                    'weather_icon' => $data['weather'][0]['icon'],
                    'description' => $data['weather'][0]['description'],
                    'humidity' => $data['main']['humidity'],
                    'pressure' => $data['main']['pressure'],
                    'wind_speed' => $data['wind']['speed'],
                ];
            } catch (\Exception $e) {
                Log::error('Weather API Error: ' . $e->getMessage());
                return null;
            }
        });
    }
    
    /**
     * 5日間予報を取得
     */
    public function getForecast($city = 'Karuizawa,jp', $days = 5)
    {
        return Cache::remember('weather_forecast_' . $city, 1800, function () use ($city, $days) {
            try {
                $client = new Client();
                $url = "{$this->base_url}/forecast?q={$city}&appid={$this->api_key}&lang=ja";
                
                $response = $client->request('GET', $url);
                $data = json_decode($response->getBody(), true);
                
                $forecast = [];
                $previous_date = null;
                
                // 3時間ごとのデータから1日1回（正午）を抽出
                foreach ($data['list'] as $item) {
                    $date = date('Y-m-d', strtotime($item['dt_txt']));
                    $hour = date('H', strtotime($item['dt_txt']));
                    
                    // 1日1回、正午のデータのみ
                    if ($date != $previous_date && $hour == '12') {
                        $forecast[] = [
                            'date' => $date,
                            'date_formatted' => date('m/d (D)', strtotime($item['dt_txt'])),
                            'temp' => round($item['main']['temp'] - 273.15),
                            'temp_max' => round($item['main']['temp_max'] - 273.15),
                            'temp_min' => round($item['main']['temp_min'] - 273.15),
                            'weather' => $this->translateWeather($item['weather'][0]['main']),
                            'weather_icon' => $item['weather'][0]['icon'],
                            'humidity' => $item['main']['humidity'],
                            'pop' => round($item['pop'] * 100), // 降水確率
                        ];
                        
                        $previous_date = $date;
                        
                        if (count($forecast) >= $days) {
                            break;
                        }
                    }
                }
                
                return $forecast;
            } catch (\Exception $e) {
                Log::error('Forecast API Error: ' . $e->getMessage());
                return [];
            }
        });
    }
    
    /**
     * 天気を日本語に変換
     */
    private function translateWeather($weather)
    {
        $translations = [
            'Clear' => '晴れ',
            'Clouds' => 'くもり',
            'Rain' => '雨',
            'Drizzle' => '小雨',
            'Thunderstorm' => '雷雨',
            'Snow' => '雪',
            'Mist' => '霧',
            'Fog' => '霧',
            'Haze' => '霞',
        ];
        
        return $translations[$weather] ?? $weather;
    }
}
```

### Step 13-2: TrafficService作成（Day 2）

```php
<?php
// app/Services/TrafficService.php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TrafficService
{
    private $api_key;
    private $base_url = 'https://maps.googleapis.com/maps/api/directions/json';
    
    public function __construct()
    {
        $this->api_key = env('GOOGLE_MAPS_API_KEY', 'AIzaSyDLOarhzAaiOZ27LhuWihrS1b2WOgJTxBY');
    }
    
    /**
     * 交通・渋滞情報を取得（キャッシュ10分）
     */
    public function getTrafficInfo(
        $departure = '軽井沢駅',
        $destination = '三ツ谷地区世代間交流センター、長野県北佐久郡御代田町馬瀬口2039-2'
    ) {
        $cache_key = 'traffic_' . md5($departure . $destination);
        
        return Cache::remember($cache_key, 600, function () use ($departure, $destination) {
            try {
                $client = new Client();
                
                $response = $client->get($this->base_url, [
                    'query' => [
                        'origin' => $departure,
                        'destination' => $destination,
                        'departure_time' => 'now',  // リアルタイム
                        'mode' => 'driving',
                        'language' => 'ja',
                        'alternatives' => 'true',
                        'key' => $this->api_key,
                    ]
                ]);
                
                $data = json_decode($response->getBody(), true);
                
                if ($data['status'] !== 'OK' || empty($data['routes'])) {
                    return null;
                }
                
                $route = $data['routes'][0];
                $leg = $route['legs'][0];
                
                return [
                    'distance' => $leg['distance']['text'],
                    'duration' => $leg['duration']['text'],
                    'duration_in_traffic' => $leg['duration_in_traffic']['text'] ?? $leg['duration']['text'],
                    'traffic_status' => $this->determineTraffic($route),
                    'start_address' => $leg['start_address'],
                    'end_address' => $leg['end_address'],
                    'route_name' => $route['summary'],
                ];
            } catch (\Exception $e) {
                Log::error('Traffic API Error: ' . $e->getMessage());
                return null;
            }
        });
    }
    
    /**
     * 渋滞判定
     */
    private function determineTraffic($route)
    {
        foreach ($route['legs'] as $leg) {
            foreach ($leg['steps'] as $step) {
                if (isset($step['traffic_speed_entry'])) {
                    $speed = $step['traffic_speed_entry'][0]['speed'];
                    
                    if ($speed < 20) {
                        return ['status' => 'heavy', 'text' => '渋滞あり', 'color' => 'red'];
                    } elseif ($speed < 40) {
                        return ['status' => 'moderate', 'text' => 'やや混雑', 'color' => 'orange'];
                    }
                }
            }
        }
        
        return ['status' => 'clear', 'text' => '渋滞なし', 'color' => 'green'];
    }
}
```

### Step 13-3: TopControllerに統合（Day 3）

```php
<?php
// app/Http/Controllers/TopController.php

use App\Services\WeatherService;

class TopController extends Controller
{
    private $freeday_service;
    private $weather_service;
    
    public function __construct(
        FreedayService $freeday_service,
        WeatherService $weather_service
    ) {
        $this->freeday_service = $freeday_service;
        $this->weather_service = $weather_service;
    }
    
    public function index()
    {
        $user = Auth::user();
        
        // 既存のデータ取得...
        
        // 天気情報取得
        $today_weather = $this->weather_service->getTodayWeather();
        $forecast = $this->weather_service->getForecast();
        
        return view('top.index', compact(
            'calendars',
            'reservations',
            'last_reservation',
            'freedays',
            'info',
            'user_point',
            'pointbalance',
            'today_weather',
            'forecast'
        ));
    }
}
```

---

## Phase 14: SPA化対応（2-3週間）※後期フェーズ

### 目標
モノリシック構成からSPA（Single Page Application）へ移行

### Step 14-1: API化準備（Day 1-3）

#### routes/api.php 実装
```php
<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReservationApiController;
use App\Http\Controllers\Api\ServiceApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\UserApiController;

// 認証必須API
Route::middleware('auth:sanctum')->group(function () {
    
    // ユーザー情報
    Route::get('/user', [UserApiController::class, 'show']);
    Route::put('/user', [UserApiController::class, 'update']);
    
    // 予約API
    Route::apiResource('reservations', ReservationApiController::class);
    Route::post('reservations/{reservation}/cancel', [ReservationApiController::class, 'cancel']);
    
    // サービスAPI
    Route::apiResource('services', ServiceApiController::class);
    
    // カートAPI
    Route::get('cart', [CartApiController::class, 'index']);
    Route::post('cart/add', [CartApiController::class, 'add']);
    Route::delete('cart/{cartDetail}', [CartApiController::class, 'remove']);
    Route::post('cart/checkout', [CartApiController::class, 'checkout']);
    
    // カレンダーAPI
    Route::get('calendars/available', [CalendarApiController::class, 'available']);
    Route::get('calendars/{year}/{month}', [CalendarApiController::class, 'monthly']);
    
    // ポイントAPI
    Route::get('points', [PointApiController::class, 'balance']);
    Route::get('points/history', [PointApiController::class, 'history']);
});

// 公開API
Route::get('news', [NewsApiController::class, 'index']);
Route::get('news/{id}', [NewsApiController::class, 'show']);
```

### Step 14-2: APIコントローラー作成（Day 4-7）

```bash
mkdir -p app/Http/Controllers/Api
php artisan make:controller Api/ReservationApiController --api
php artisan make:controller Api/ServiceApiController --api
php artisan make:controller Api/CartApiController --api
```

```php
<?php
// app/Http/Controllers/Api/ReservationApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Http\Resources\ReservationResource;
use App\Consts\ReservationConst;
use Illuminate\Http\Request;

class ReservationApiController extends Controller
{
    /**
     * 予約一覧取得
     */
    public function index(Request $request)
    {
        $reservations = Reservation::where('user_id', $request->user()->id)
            ->with(['hotel', 'orders.orderDetails.service'])
            ->orderBy('checkin_date', 'desc')
            ->paginate(20);
        
        return ReservationResource::collection($reservations);
    }
    
    /**
     * 予約詳細取得
     */
    public function show(Request $request, $id)
    {
        $reservation = Reservation::with(['hotel', 'orders.orderDetails'])
            ->findOrFail($id);
        
        // 権限チェック
        if ($reservation->user_id != $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return new ReservationResource($reservation);
    }
    
    /**
     * 予約作成
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'calendar_id' => 'required|exists:calendars,id',
            'adult' => 'required|integer|min:1|max:10',
            'child' => 'nullable|integer|min:0|max:10',
            'dog' => 'nullable|integer|min:0|max:5',
            'note' => 'nullable|string|max:500',
        ]);
        
        // 予約作成ロジック...
        
        return new ReservationResource($reservation);
    }
    
    /**
     * 予約キャンセル
     */
    public function cancel(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if ($reservation->user_id != $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $reservation->update(['status' => ReservationConst::STATUS_CANCEL]);
        
        return response()->json([
            'message' => '予約をキャンセルしました',
            'data' => new ReservationResource($reservation)
        ]);
    }
}
```

### Step 14-3: APIリソース作成（Day 8-9）

```bash
php artisan make:resource ReservationResource
php artisan make:resource ServiceResource
php artisan make:resource OrderResource
```

```php
<?php
// app/Http/Resources/ReservationResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'hotel' => [
                'id' => $this->hotel->id,
                'name' => $this->hotel->name,
            ],
            'checkin_date' => $this->checkin_date,
            'checkout_date' => $this->checkout_date,
            'days' => $this->days,
            'guests' => [
                'adult' => $this->adult,
                'child' => $this->child,
                'dog' => $this->dog,
            ],
            'room_key' => $this->room_key,
            'payment' => $this->payment,
            'payment_text' => $this->payment == 0 ? '現地払い' : 'クレジット',
            'status' => $this->status,
            'status_text' => \App\Consts\ReservationConst::STATUS_LIST[$this->status] ?? '',
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'total_price' => $this->orders->sum('total_price'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
```

### Step 14-4: React実装（Day 10-15）※外部エンジニア協力

```bash
# Reactセットアップ
npm install react react-dom
npm install @vitejs/plugin-react
npm install axios react-router-dom
```

```jsx
// resources/js/components/ReservationList.jsx

import React, { useEffect, useState } from 'react';
import axios from 'axios';

const ReservationList = () => {
    const [reservations, setReservations] = useState([]);
    const [loading, setLoading] = useState(true);
    
    useEffect(() => {
        fetchReservations();
    }, []);
    
    const fetchReservations = async () => {
        try {
            const response = await axios.get('/api/reservations', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });
            setReservations(response.data.data);
            setLoading(false);
        } catch (error) {
            console.error('Error fetching reservations:', error);
            setLoading(false);
        }
    };
    
    if (loading) {
        return <div>読み込み中...</div>;
    }
    
    return (
        <div className="reservation-list">
            <h2>予約一覧</h2>
            {reservations.map(reservation => (
                <div key={reservation.id} className="reservation-card">
                    <h3>{reservation.hotel.name}</h3>
                    <p>
                        {reservation.checkin_date} ～ {reservation.checkout_date}
                        ({reservation.days}泊)
                    </p>
                    <p>大人{reservation.guests.adult}名</p>
                    <span className={`status status-${reservation.status}`}>
                        {reservation.status_text}
                    </span>
                </div>
            ))}
        </div>
    );
};

export default ReservationList;
```

---

## Phase 15: テスト・品質向上（2週間）

### 目標
テストコードの実装と品質向上

### Step 15-1: Feature Test実装（Day 1-5）

```bash
php artisan make:test ReservationTest
php artisan make:test OrderTest
php artisan make:test CartTest
php artisan make:test PointTest
php artisan make:test AuthTest
```

#### ReservationTest
```php
<?php
// tests/Feature/ReservationTest.php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Calendar;
use App\Models\Reservation;
use App\Consts\ReservationConst;

class ReservationTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // テストデータ作成
        $this->user = User::factory()->create([
            'type' => 2, // オーナー
        ]);
        
        $this->hotel = Hotel::factory()->create();
        
        $this->user->hotels()->attach($this->hotel->id);
    }
    
    /**
     * 予約一覧ページが表示されるか
     */
    public function test_reservation_index_page_can_be_displayed()
    {
        $response = $this->actingAs($this->user)
            ->get(route('reservation.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('reservation.index');
    }
    
    /**
     * 予約が作成できるか
     */
    public function test_user_can_create_reservation()
    {
        $calendar = Calendar::factory()->create([
            'user_id' => $this->user->id,
            'hotel_id' => $this->hotel->id,
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(8),
            'status' => 1,
        ]);
        
        $response = $this->actingAs($this->user)
            ->post(route('reservation.store'), [
                'calendar_id' => $calendar->id,
                'adult' => 2,
                'child' => 1,
                'payment' => 0,
            ]);
        
        $response->assertRedirect(route('reservation.complete'));
        
        $this->assertDatabaseHas('reservations', [
            'user_id' => $this->user->id,
            'calendar_id' => $calendar->id,
            'adult' => 2,
            'child' => 1,
        ]);
    }
    
    /**
     * バリデーションエラーのテスト
     */
    public function test_reservation_requires_adult_count()
    {
        $calendar = Calendar::factory()->create([
            'user_id' => $this->user->id,
            'hotel_id' => $this->hotel->id,
        ]);
        
        $response = $this->actingAs($this->user)
            ->post(route('reservation.store'), [
                'calendar_id' => $calendar->id,
                'adult' => 0, // エラー
                'payment' => 0,
            ]);
        
        $response->assertSessionHasErrors(['adult']);
    }
    
    /**
     * 予約キャンセルのテスト
     */
    public function test_user_can_cancel_reservation()
    {
        $reservation = Reservation::factory()->create([
            'user_id' => $this->user->id,
            'hotel_id' => $this->hotel->id,
            'status' => ReservationConst::STATUS_RESERVED,
        ]);
        
        $response = $this->actingAs($this->user)
            ->post(route('reservation.cancel', $reservation));
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => ReservationConst::STATUS_CANCEL,
        ]);
    }
    
    /**
     * 他人の予約はキャンセルできない
     */
    public function test_user_cannot_cancel_others_reservation()
    {
        $otherUser = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $otherUser->id,
        ]);
        
        $response = $this->actingAs($this->user)
            ->post(route('reservation.cancel', $reservation));
        
        $response->assertStatus(403);
    }
}
```

### Step 15-2: Unit Test実装（Day 6-7）

```php
<?php
// tests/Unit/PointServiceTest.php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PointService;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserPointLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PointServiceTest extends TestCase
{
    use RefreshDatabase;
    
    protected $pointService;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->pointService = new PointService();
    }
    
    /**
     * ポイント付与のテスト
     */
    public function test_can_add_points()
    {
        $user = User::factory()->create();
        
        $this->pointService->addPoint(
            $user->id,
            100,
            'テスト付与',
            now()->format('Y-m-d'),
            now()->addYear()->format('Y-m-d')
        );
        
        $this->assertDatabaseHas('user_points', [
            'user_id' => $user->id,
            'point' => 100,
        ]);
        
        $this->assertDatabaseHas('user_point_logs', [
            'user_id' => $user->id,
            'point' => 100,
            'type' => 1, // 加算
        ]);
    }
    
    /**
     * ポイント利用のテスト
     */
    public function test_can_use_points()
    {
        $user = User::factory()->create();
        
        // ポイント付与
        $this->pointService->addPoint(
            $user->id,
            200,
            'テスト付与',
            now()->format('Y-m-d'),
            now()->addYear()->format('Y-m-d')
        );
        
        // ポイント利用
        $this->pointService->usePoint($user->id, 50, 'テスト利用');
        
        // 残高確認
        $balance = $this->pointService->getAvailablePoints($user->id);
        $this->assertEquals(150, $balance);
    }
    
    /**
     * ポイント不足のテスト
     */
    public function test_cannot_use_more_points_than_available()
    {
        $this->expectException(\Exception::class);
        
        $user = User::factory()->create();
        
        $this->pointService->addPoint($user->id, 50, 'テスト', now(), now()->addYear());
        
        // 100ポイント使おうとする（50しかない）
        $this->pointService->usePoint($user->id, 100, 'テスト利用');
    }
}
```

### Step 15-3: コード品質向上（Day 8-10）

#### Laravel Debugbarでパフォーマンスチェック
```bash
composer require barryvdh/laravel-debugbar --dev
```

```php
// config/debugbar.php（自動生成）
return [
    'enabled' => env('DEBUGBAR_ENABLED', null),
];
```

#### N+1問題の検出と修正
```php
// Before（悪い例）- 90回クエリ
public function index()
{
    $reservations = Reservation::all(); // 1回
    
    foreach ($reservations as $reservation) {
        echo $reservation->hotel->name;  // N回
        echo $reservation->user->name;   // N回
        
        foreach ($reservation->orders as $order) {  // N回
            echo $order->service->title;  // N*M回
        }
    }
}

// After（良い例）- 4回クエリ
public function index()
{
    $reservations = Reservation::with([
        'hotel',
        'user',
        'orders.service'
    ])->get();  // 4回（reservations, hotels, users, orders, services）
    
    foreach ($reservations as $reservation) {
        echo $reservation->hotel->name;  // クエリなし
        echo $reservation->user->name;   // クエリなし
        
        foreach ($reservation->orders as $order) {
            echo $order->service->title;  // クエリなし
        }
    }
}
```

---

## Phase 16: デプロイ・運用（1週間）

### 目標
本番環境への安全なデプロイ

### Step 16-1: 本番環境設定（Day 1-2）

#### .env.production 作成
```env
APP_NAME="空ノ庭 予約システム"
APP_ENV=production
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=false
APP_URL=https://soranoniwa.jp

LOG_CHANNEL=daily
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=soranoniwa_production
DB_USERNAME=soranoniwa_user
DB_PASSWORD=STRONG_PASSWORD_HERE

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DRIVER=public
QUEUE_CONNECTION=database
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@soranoniwa.jp
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@soranoniwa.jp
MAIL_FROM_NAME="${APP_NAME}"

# Veritrans本番設定
VERITRANS_MERCHANT_ID=production_merchant_id
VERITRANS_MERCHANT_PASS=production_password
VERITRANS_MERCHANT_CCID=production_ccid
VERITRANS_MERCHANT_SECRET_KEY=production_secret_key

# 外部API
OPENWEATHER_API_KEY=your_openweather_key
GOOGLE_MAPS_API_KEY=your_google_maps_key
```

### Step 16-2: デプロイスクリプト作成（Day 3）

```bash
# deploy.sh
#!/bin/bash

echo "===== 空ノ庭 デプロイ開始 ====="

# 1. Gitプル
echo "▶ Gitプル..."
git pull origin main

# 2. Composer依存関係更新
echo "▶ Composer更新..."
composer install --no-dev --optimize-autoloader

# 3. npm依存関係更新
echo "▶ npm更新..."
npm install
npm run production

# 4. メンテナンスモード開始
echo "▶ メンテナンスモード開始..."
php artisan down

# 5. キャッシュクリア
echo "▶ キャッシュクリア..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 6. マイグレーション実行
echo "▶ マイグレーション実行..."
php artisan migrate --force

# 7. キャッシュ最適化
echo "▶ キャッシュ最適化..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. ストレージリンク
echo "▶ ストレージリンク..."
php artisan storage:link

# 9. メンテナンスモード終了
echo "▶ メンテナンスモード終了..."
php artisan up

echo "===== デプロイ完了 ====="
```

```bash
chmod +x deploy.sh
```

### Step 16-3: 本番環境最適化（Day 4）

#### 1. OPcacheの有効化
```ini
# infra/docker/php/php.deploy.ini に追加

[opcache]
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

#### 2. データベース最適化
```sql
-- インデックス追加
ALTER TABLE reservations ADD INDEX idx_user_status (user_id, status);
ALTER TABLE reservations ADD INDEX idx_checkin_date (checkin_date);
ALTER TABLE orders ADD INDEX idx_reservation_id (reservation_id);
ALTER TABLE user_points ADD INDEX idx_user_to (user_id, to);
```

#### 3. Redisセッション設定
```bash
composer require predis/predis
```

```php
// config/session.php
'driver' => env('SESSION_DRIVER', 'redis'),
```

### Step 16-4: セキュリティ対策（Day 5）

#### 1. HTTPS強制
```php
// app/Providers/AppServiceProvider.php

use Illuminate\Support\Facades\URL;

public function boot()
{
    if ($this->app->environment('production')) {
        URL::forceScheme('https');
    }
}
```

#### 2. CSP（Content Security Policy）設定
```php
// app/Http/Middleware/SecurityHeaders.php

namespace App\Http\Middleware;

use Closure;

class SecurityHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        return $response;
    }
}
```

#### 3. レート制限
```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'web' => [
        // ...
        \Illuminate\Routing\Middleware\ThrottleRequests::class.':60,1',
    ],
    
    'api' => [
        \Illuminate\Routing\Middleware\ThrottleRequests::class.':60,1',
    ],
];
```

### Step 16-5: 監視・ログ設定（Day 6-7）

#### ログローテーション設定
```php
// config/logging.php

'channels' => [
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14, // 14日間保持
    ],
],
```

#### エラー通知設定（オプション）
```bash
composer require sentry/sentry-laravel
```

```php
// config/sentry.php
'dsn' => env('SENTRY_LARAVEL_DSN'),
```

### チェックリスト（Phase 16）

#### デプロイ前
- [ ] 本番.env設定完了
- [ ] データベースバックアップ取得
- [ ] デプロイスクリプトテスト
- [ ] SSL証明書設定確認

#### デプロイ後
- [ ] 全機能の動作確認
- [ ] 決済テスト（本番API）
- [ ] メール送信テスト
- [ ] パフォーマンス確認
- [ ] エラーログ監視

#### 継続監視
- [ ] ログ監視体制
- [ ] バックアップ自動化
- [ ] アップデート計画
- [ ] セキュリティパッチ適用

---

## 🎉 実装完了！

全フェーズを完了すれば、完全な会員制宿泊予約システムが完成します。

### 最終確認項目

#### 機能面
- [ ] ユーザー登録・ログイン
- [ ] 予約作成・確認・キャンセル
- [ ] サービス注文
- [ ] カート機能
- [ ] クレジット決済
- [ ] ポイント付与・利用
- [ ] 招待機能
- [ ] 管理画面

#### 非機能面
- [ ] パフォーマンス（ページ読み込み3秒以内）
- [ ] セキュリティ（脆弱性対策）
- [ ] 可用性（エラー処理）
- [ ] 保守性（コード品質）

おめでとうございます！🎊

