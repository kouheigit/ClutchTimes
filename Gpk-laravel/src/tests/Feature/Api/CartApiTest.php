<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * カート一覧取得のテスト
     */
    public function test_can_get_cart()
    {
        Sanctum::actingAs($this->user);

        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        CartDetail::factory()->count(3)->create(['cart_id' => $cart->id]);

        $response = $this->getJson('/api/cart');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'cart_details',
                    'total_price',
                ]
            ]);
    }

    /**
     * カートに追加のテスト
     */
    public function test_can_add_to_cart()
    {
        Sanctum::actingAs($this->user);

        $service = Service::factory()->create([
            'stock' => 10,
            'minimum' => 1,
        ]);

        $data = [
            'service_id' => $service->id,
            'quantity' => 2,
        ];

        $response = $this->postJson('/api/cart/add', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'service',
                    'quantity',
                    'total_price',
                ]
            ]);

        $this->assertDatabaseHas('cart_details', [
            'service_id' => $service->id,
            'quantity' => 2,
        ]);
    }

    /**
     * カートから削除のテスト
     */
    public function test_can_remove_from_cart()
    {
        Sanctum::actingAs($this->user);

        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        $cartDetail = CartDetail::factory()->create(['cart_id' => $cart->id]);

        $response = $this->deleteJson("/api/cart/{$cartDetail->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'カートから削除しました',
            ]);

        $this->assertDatabaseMissing('cart_details', [
            'id' => $cartDetail->id,
        ]);
    }

    /**
     * カート数量更新のテスト
     */
    public function test_can_update_cart_quantity()
    {
        Sanctum::actingAs($this->user);

        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        $cartDetail = CartDetail::factory()->create([
            'cart_id' => $cart->id,
            'quantity' => 1,
        ]);

        $data = [
            'quantity' => 3,
        ];

        $response = $this->putJson("/api/cart/{$cartDetail->id}", $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'quantity',
                    'total_price',
                ]
            ]);

        $this->assertDatabaseHas('cart_details', [
            'id' => $cartDetail->id,
            'quantity' => 3,
        ]);
    }
}

