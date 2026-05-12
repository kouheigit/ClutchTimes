<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Calendar;
use App\Models\Hotel;
use App\Models\Holiday;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class CalendarApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * 利用可能なカレンダー取得のテスト
     */
    public function test_can_get_available_calendars()
    {
        Sanctum::actingAs($this->user);

        $hotel = Hotel::factory()->create();
        Calendar::factory()->count(5)->create([
            'hotel_id' => $hotel->id,
            'user_id' => $this->user->id,
            'status' => 1,
        ]);

        $response = $this->getJson('/api/calendars/available');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'calendars' => [
                    '*' => [
                        'id',
                        'hotel',
                        'start_date',
                        'end_date',
                    ]
                ]
            ]);
    }

    /**
     * 月次カレンダー取得のテスト
     */
    public function test_can_get_monthly_calendar()
    {
        Sanctum::actingAs($this->user);

        $hotel = Hotel::factory()->create();
        $year = now()->year;
        $month = now()->month;

        Calendar::factory()->create([
            'hotel_id' => $hotel->id,
            'user_id' => $this->user->id,
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->endOfMonth(),
        ]);

        Holiday::factory()->create([
            'date' => now()->startOfMonth()->addDays(5),
        ]);

        $response = $this->getJson("/api/calendars/{$year}/{$month}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'year',
                'month',
                'calendars',
                'holidays',
                'reservations',
            ]);
    }

    /**
     * 日付範囲フィルターのテスト
     */
    public function test_can_filter_calendars_by_date_range()
    {
        Sanctum::actingAs($this->user);

        $hotel = Hotel::factory()->create();
        Calendar::factory()->create([
            'hotel_id' => $hotel->id,
            'user_id' => $this->user->id,
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(15),
            'status' => 1,
        ]);

        $fromDate = now()->addDays(5)->format('Y-m-d');
        $toDate = now()->addDays(20)->format('Y-m-d');

        $response = $this->getJson("/api/calendars/available?from_date={$fromDate}&to_date={$toDate}");

        $response->assertStatus(200);
        $this->assertGreaterThan(0, count($response->json('calendars')));
    }
}

