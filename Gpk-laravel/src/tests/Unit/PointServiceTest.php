<?php

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
        
        $this->pointService->addPoint(
            $user->id, 
            50, 
            'テスト', 
            now()->format('Y-m-d'), 
            now()->addYear()->format('Y-m-d')
        );
        
        // 100ポイント使おうとする（50しかない）
        $this->pointService->usePoint($user->id, 100, 'テスト利用');
    }
    
    /**
     * 利用可能ポイント取得のテスト
     */
    public function test_get_available_points()
    {
        $user = User::factory()->create();
        
        // 有効なポイント付与
        $this->pointService->addPoint(
            $user->id,
            100,
            '有効ポイント',
            now()->format('Y-m-d'),
            now()->addYear()->format('Y-m-d')
        );
        
        // 期限切れポイント付与
        $this->pointService->addPoint(
            $user->id,
            50,
            '期限切れポイント',
            now()->subYear()->format('Y-m-d'),
            now()->subDays(1)->format('Y-m-d')
        );
        
        $balance = $this->pointService->getAvailablePoints($user->id);
        $this->assertEquals(100, $balance);
    }
}

