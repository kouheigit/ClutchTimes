<?php

namespace Database\Factories;

use App\Models\CartDetail;
use App\Models\Cart;
use App\Models\Service;
use App\Models\ServiceOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartDetailFactory extends Factory
{
    protected $model = CartDetail::class;

    public function definition()
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $price = $this->faker->numberBetween(1000, 10000);
        
        return [
            'cart_id' => Cart::factory(),
            'service_id' => Service::factory(),
            'service_option_id' => null,
            'price' => $price,
            'quantity' => $quantity,
            'total_price' => $price * $quantity,
        ];
    }

    public function withOption()
    {
        return $this->state(function (array $attributes) {
            return [
                'service_option_id' => ServiceOption::factory(),
            ];
        });
    }
}

