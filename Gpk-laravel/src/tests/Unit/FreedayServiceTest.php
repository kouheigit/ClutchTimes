<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\FreedayService;
use App\Models\User;
use App\Models\Freeday;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class FreedayServiceTest extends TestCase
{
    use RefreshDatabase;
    
    protected $freedayService;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->freedayService = new FreedayService();
    }
    
    /**
     * 有効なフリーデイを取得できるか
     */
    public function test_can_get_freedays()
    {
        $user = User::factory()->create();
        
        // 有効なフリーデイ
        Freeday::create([
            'user_id' => $user->id,
            'start_date' => now()->subDays(10),
            'end_date' => now()->addDays(30),
            'freedays' => 5,
            'status' => 1,
        ]);
        
        // 期限切れフリーデイ
        Freeday::create([
            'user_id' => $user->id,
            'start_date' => now()->subDays(30),
            'end_date' => now()->subDays(1),
            'freedays' => 3,
            'status' => 1,
        ]);
        
        $freedays = $this->freedayService->getFreedays($user);
        
        $this->assertCount(1, $freedays);
        $this->assertEquals(5, $freedays->first()->freedays);
    }
    
    /**
     * 今年度の最大フリーデイ泊数を取得できるか
     */
    public function test_can_get_year_max_freedays_num()
    {
        $user = User::factory()->create();
        
        Freeday::create([
            'user_id' => $user->id,
            'start_date' => now()->startOfYear(),
            'end_date' => now()->endOfYear(),
            'freedays' => 10,
            'status' => 1,
        ]);
        
        Freeday::create([
            'user_id' => $user->id,
            'start_date' => now()->startOfYear(),
            'end_date' => now()->endOfYear(),
            'freedays' => 5,
            'status' => 1,
        ]);
        
        $maxFreedays = $this->freedayService->getYearMaxFreedaysNum($user);
        
        $this->assertEquals(15, $maxFreedays);
    }
    
    /**
     * フリーデイ利用可能チェック
     */
    public function test_can_use_freeday()
    {
        $user = User::factory()->create();
        
        $freeday = Freeday::create([
            'user_id' => $user->id,
            'start_date' => now()->subMonths(12),
            'end_date' => now()->addDays(30),
            'freedays' => 5,
            'status' => 1,
        ]);
        
        $canUse = $this->freedayService->canUseFreeday($freeday, 3);
        
        $this->assertTrue($canUse);
    }
    
    /**
     * フリーデイ利用不可（残り泊数不足）
     */
    public function test_cannot_use_freeday_insufficient_days()
    {
        $user = User::factory()->create();
        
        $freeday = Freeday::create([
            'user_id' => $user->id,
            'start_date' => now()->subMonths(12),
            'end_date' => now()->addDays(30),
            'freedays' => 2,
            'status' => 1,
        ]);
        
        $canUse = $this->freedayService->canUseFreeday($freeday, 3);
        
        $this->assertFalse($canUse);
    }
    
    /**
     * フリーデイ消費のテスト
     */
    public function test_can_consume_freeday()
    {
        $user = User::factory()->create();
        
        $freeday = Freeday::create([
            'user_id' => $user->id,
            'start_date' => now()->subMonths(12),
            'end_date' => now()->addDays(30),
            'freedays' => 5,
            'status' => 1,
        ]);
        
        $this->freedayService->consumeFreeday($freeday, 2);
        
        $freeday->refresh();
        $this->assertEquals(3, $freeday->freedays);
    }
    
    /**
     * フリーデイ返却のテスト
     */
    public function test_can_return_freeday()
    {
        $user = User::factory()->create();
        
        $freeday = Freeday::create([
            'user_id' => $user->id,
            'start_date' => now()->subMonths(12),
            'end_date' => now()->addDays(30),
            'freedays' => 3,
            'status' => 1,
        ]);
        
        $this->freedayService->returnFreeday($freeday, 2);
        
        $freeday->refresh();
        $this->assertEquals(5, $freeday->freedays);
    }
}

