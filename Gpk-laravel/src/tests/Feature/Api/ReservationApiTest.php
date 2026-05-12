<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Hotel;
use App\Models\Calendar;
use App\Consts\ReservationConst;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class ReservationApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * 予約一覧取得のテスト
     */
    public function test_can_get_reservations()
    {
        Sanctum::actingAs($this->user);

        Reservation::factory()->count(5)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->getJson('/api/reservations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'hotel',
                        'checkin_date',
                        'checkout_date',
                        'status',
                    ]
                ]
            ]);
    }

    /**
     * 予約詳細取得のテスト
     */
    public function test_can_get_reservation_detail()
    {
        Sanctum::actingAs($this->user);

        $reservation = Reservation::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->getJson("/api/reservations/{$reservation->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'hotel',
                    'user',
                    'checkin_date',
                    'checkout_date',
                ]
            ]);
    }

    /**
     * 予約作成のテスト
     */
    public function test_can_create_reservation()
    {
        Sanctum::actingAs($this->user);

        $hotel = Hotel::factory()->create();
        $calendar = Calendar::factory()->create([
            'hotel_id' => $hotel->id,
            'user_id' => $this->user->id,
        ]);

        $data = [
            'hotel_id' => $hotel->id,
            'calendar_id' => $calendar->id,
            'checkin_date' => now()->addDays(7)->format('Y-m-d'),
            'checkout_date' => now()->addDays(9)->format('Y-m-d'),
            'adult' => 2,
            'child' => 1,
            'dog' => 0,
            'note' => 'テスト予約',
            'payment' => 0,
        ];

        $response = $this->postJson('/api/reservations', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'hotel',
                    'checkin_date',
                    'checkout_date',
                ]
            ]);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $this->user->id,
            'hotel_id' => $hotel->id,
            'adult' => 2,
        ]);
    }

    /**
     * 予約キャンセルのテスト
     */
    public function test_can_cancel_reservation()
    {
        Sanctum::actingAs($this->user);

        $reservation = Reservation::factory()->create([
            'user_id' => $this->user->id,
            'status' => ReservationConst::STATUS_RESERVED,
        ]);

        $response = $this->postJson("/api/reservations/{$reservation->id}/cancel");

        $response->assertStatus(200)
            ->assertJson([
                'message' => '予約をキャンセルしました',
            ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => ReservationConst::STATUS_CANCEL,
        ]);
    }

    /**
     * 他のユーザーの予約にアクセスできないテスト
     */
    public function test_cannot_access_other_user_reservation()
    {
        Sanctum::actingAs($this->user);

        $otherUser = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->getJson("/api/reservations/{$reservation->id}");

        $response->assertStatus(403);
    }
}

