<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserPointLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Carbon\Carbon;

class PointApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * ポイント残高取得のテスト
     */
    public function test_can_get_point_balance()
    {
        Sanctum::actingAs($this->user);

        UserPoint::factory()->create([
            'user_id' => $this->user->id,
            'point' => 1000,
            'from' => Carbon::now(),
            'to' => Carbon::now()->addYear(),
        ]);

        $response = $this->getJson('/api/points');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_points',
                'balance_by_expiry' => [
                    '*' => [
                        'id',
                        'point',
                        'from',
                        'to',
                    ]
                ]
            ]);
    }

    /**
     * ポイント履歴取得のテスト
     */
    public function test_can_get_point_history()
    {
        Sanctum::actingAs($this->user);

        UserPointLog::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'type' => 1, // 加算
        ]);

        $response = $this->getJson('/api/points/history');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'point',
                        'type',
                        'reason',
                    ]
                ]
            ]);
    }

    /**
     * タイプフィルターのテスト
     */
    public function test_can_filter_point_history_by_type()
    {
        Sanctum::actingAs($this->user);

        UserPointLog::factory()->create([
            'user_id' => $this->user->id,
            'type' => 1, // 加算
        ]);

        UserPointLog::factory()->create([
            'user_id' => $this->user->id,
            'type' => 2, // 減算
        ]);

        $response = $this->getJson('/api/points/history?type=1');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    /**
     * 日付範囲フィルターのテスト
     */
    public function test_can_filter_point_history_by_date_range()
    {
        Sanctum::actingAs($this->user);

        UserPointLog::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => now()->subDays(10),
        ]);

        $fromDate = now()->subDays(5)->format('Y-m-d');
        $toDate = now()->format('Y-m-d');

        $response = $this->getJson("/api/points/history?from_date={$fromDate}&to_date={$toDate}");

        $response->assertStatus(200);
    }
}

