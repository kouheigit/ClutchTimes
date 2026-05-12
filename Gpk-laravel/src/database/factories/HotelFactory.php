<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company() . ' ホテル',
            'address' => $this->faker->address(),
            'description' => $this->faker->text(200),
            'status' => 1,
        ];
    }
}

