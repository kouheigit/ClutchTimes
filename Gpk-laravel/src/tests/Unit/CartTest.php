<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\User;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /**
     * カートと明細のリレーションテスト
     */
    public function test_cart_has_details()
    {
        $cart = Cart::factory()->create();
        $detail = CartDetail::factory()->create([
            'cart_id' => $cart->id,
        ]);

        $this->assertTrue($cart->cartDetails->contains($detail));
    }

    /**
     * カートの合計金額計算テスト
     */
    public function test_cart_total_price()
    {
        $cart = Cart::factory()->create();
        CartDetail::factory()->create([
            'cart_id' => $cart->id,
            'total_price' => 1000,
        ]);
        CartDetail::factory()->create([
            'cart_id' => $cart->id,
            'total_price' => 2000,
        ]);

        $total = $cart->cartDetails->sum('total_price');
        $this->assertEquals(3000, $total);
    }
}

