<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Service;
use App\Models\ServiceOption;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * サービスとオプションのリレーションテスト
     */
    public function test_service_has_options()
    {
        $service = Service::factory()->create();
        $option = ServiceOption::factory()->create([
            'service_id' => $service->id,
        ]);

        $this->assertTrue($service->serviceOptions->contains($option));
    }

    /**
     * サービスの在庫チェックテスト
     */
    public function test_service_stock_check()
    {
        $service = Service::factory()->create([
            'stock' => 10,
        ]);

        $this->assertTrue($service->stock > 0);
        $this->assertEquals(10, $service->stock);
    }

    /**
     * サービスの最小注文数チェックテスト
     */
    public function test_service_minimum_order()
    {
        $service = Service::factory()->create([
            'minimum' => 2,
            'unit' => '個',
        ]);

        $this->assertEquals(2, $service->minimum);
        $this->assertEquals('個', $service->unit);
    }
}

