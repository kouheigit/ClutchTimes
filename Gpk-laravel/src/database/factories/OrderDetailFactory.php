<?php

namespace Database\Factories;

use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderDetailFactory extends Factory
{
    protected $model = OrderDetail::class;

    public function definition()
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $price = $this->faker->numberBetween(1000, 10000);
        
        return [
            'order_id' => Order::factory(),
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

