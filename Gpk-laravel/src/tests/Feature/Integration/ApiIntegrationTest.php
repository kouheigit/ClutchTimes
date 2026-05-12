<?php

namespace Tests\Feature\Integration;

use Tests\TestCase;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Calendar;
use App\Models\Service;
use App\Models\Cart;
use App\Models\CartDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class ApiIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * 予約から注文までの統合テスト
     */
    public function test_reservation_to_order_flow()
    {
        Sanctum::actingAs($this->user);

        $hotel = Hotel::factory()->create();
        $calendar = Calendar::factory()->create([
            'hotel_id' => $hotel->id,
            'user_id' => $this->user->id,
        ]);

        // 1. 予約作成
        $reservationData = [
            'hotel_id' => $hotel->id,
            'calendar_id' => $calendar->id,
            'checkin_date' => now()->addDays(7)->format('Y-m-d'),
            'checkout_date' => now()->addDays(9)->format('Y-m-d'),
            'adult' => 2,
            'child' => 1,
            'dog' => 0,
            'payment' => 0,
        ];

        $reservationResponse = $this->postJson('/api/reservations', $reservationData);
        $reservationResponse->assertStatus(201);
        $reservationId = $reservationResponse->json('data.id');

        // 2. サービスをカートに追加
        $service = Service::factory()->create([
            'hotel_id' => $hotel->id,
        ]);

        $cartResponse = $this->postJson('/api/cart/add', [
            'service_id' => $service->id,
            'quantity' => 2,
            'reservation_id' => $reservationId,
        ]);
        $cartResponse->assertStatus(201);

        // 3. カート確認
        $cartCheckResponse = $this->getJson('/api/cart');
        $cartCheckResponse->assertStatus(200);
        $this->assertGreaterThan(0, count($cartCheckResponse->json('data.cart_details')));

        // 4. 注文確定
        $checkoutResponse = $this->postJson('/api/cart/checkout', [
            'reservation_id' => $reservationId,
            'payment' => 0,
        ]);
        $checkoutResponse->assertStatus(201);

        // 5. 予約詳細確認
        $reservationDetailResponse = $this->getJson("/api/reservations/{$reservationId}");
        $reservationDetailResponse->assertStatus(200);
    }

    /**
     * ポイント付与から利用までの統合テスト
     */
    public function test_point_flow()
    {
        Sanctum::actingAs($this->user);

        // 1. ポイント残高確認（初期状態）
        $balanceResponse = $this->getJson('/api/points');
        $balanceResponse->assertStatus(200);
        $initialBalance = $balanceResponse->json('total_points');

        // 2. ポイント履歴確認
        $historyResponse = $this->getJson('/api/points/history');
        $historyResponse->assertStatus(200);
    }
}

