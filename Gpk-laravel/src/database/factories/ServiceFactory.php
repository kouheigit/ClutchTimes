<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        return [
            'hotel_id' => Hotel::factory(),
            'title' => $this->faker->sentence(3),
            'body' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'stock' => $this->faker->numberBetween(0, 100),
            'minimum' => 1,
            'unit' => '個',
            'tab' => $this->faker->randomElement([1, 2]), // 1: 事前予約, 2: 現地注文
            'sort' => $this->faker->numberBetween(1, 100),
            'status' => 1,
        ];
    }

    public function preReservation()
    {
        return $this->state(function (array $attributes) {
            return [
                'tab' => 1,
            ];
        });
    }

    public function onSiteOrder()
    {
        return $this->state(function (array $attributes) {
            return [
                'tab' => 2,
            ];
        });
    }
}

