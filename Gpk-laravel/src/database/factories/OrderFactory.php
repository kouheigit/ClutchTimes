<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $price = $this->faker->numberBetween(1000, 10000);
        
        return [
            'user_id' => User::factory(),
            'reservation_id' => Reservation::factory(),
            'service_id' => Service::factory(),
            'price' => $price,
            'quantity' => $quantity,
            'total_price' => $price * $quantity,
            'payment' => $this->faker->randomElement([0, 1]), // 0: 現地払い, 1: クレジット
            'payment_status' => $this->faker->randomElement([0, 1]), // 0: 未決済, 1: 決済済
            'type' => $this->faker->randomElement([1, 2]), // 1: 事前予約, 2: 現地注文
            'status' => 1,
        ];
    }

    public function preReservation()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 1,
            ];
        });
    }

    public function onSiteOrder()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 2,
            ];
        });
    }

    public function paid()
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_status' => 1,
            ];
        });
    }
}

