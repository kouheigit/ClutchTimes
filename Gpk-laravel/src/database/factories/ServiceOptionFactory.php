<?php

namespace Database\Factories;

use App\Models\ServiceOption;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceOptionFactory extends Factory
{
    protected $model = ServiceOption::class;

    public function definition()
    {
        return [
            'service_id' => Service::factory(),
            'title' => $this->faker->word(),
            'price' => $this->faker->numberBetween(0, 2000),
            'sort' => $this->faker->numberBetween(1, 10),
            'status' => 1,
        ];
    }
}

