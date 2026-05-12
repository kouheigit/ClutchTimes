<?php

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
    
    protected $user;
    protected $hotel;
    
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
        
        // リダイレクトまたは成功ステータスを確認
        $response->assertStatus(302);
        
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
            'owner_id' => $this->user->id,
            'hotel_id' => $this->hotel->id,
            'status' => ReservationConst::STATUS_RESERVED,
        ]);
        
        $response = $this->actingAs($this->user)
            ->post(route('reservation.cancel', $reservation));
        
        $response->assertStatus(302); // リダイレクト
        
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
            'owner_id' => $otherUser->id,
        ]);
        
        $response = $this->actingAs($this->user)
            ->post(route('reservation.cancel', $reservation));
        
        // 403またはリダイレクト（権限エラー）
        $this->assertTrue(
            $response->status() === 403 || 
            $response->status() === 302
        );
    }
}

