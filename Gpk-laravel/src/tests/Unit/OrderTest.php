<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 注文と明細のリレーションテスト
     */
    public function test_order_has_details()
    {
        $order = Order::factory()->create();
        $detail = OrderDetail::factory()->create([
            'order_id' => $order->id,
        ]);

        $this->assertTrue($order->orderDetails->contains($detail));
    }

    /**
     * 注文の合計金額計算テスト
     */
    public function test_order_total_price()
    {
        $order = Order::factory()->create([
            'total_price' => 5000,
        ]);

        $this->assertEquals(5000, $order->total_price);
    }

    /**
     * 事前予約タイプの注文テスト
     */
    public function test_pre_reservation_order()
    {
        $order = Order::factory()->preReservation()->create();

        $this->assertEquals(1, $order->type);
    }

    /**
     * 現地注文タイプの注文テスト
     */
    public function test_on_site_order()
    {
        $order = Order::factory()->onSiteOrder()->create();

        $this->assertEquals(2, $order->type);
    }
}

