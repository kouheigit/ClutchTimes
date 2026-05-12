<?php

namespace Database\Factories;

use App\Models\Information;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class InformationFactory extends Factory
{
    protected $model = Information::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
            'publish_date' => Carbon::now()->subDays($this->faker->numberBetween(0, 30)),
            'status' => 1,
            'sort' => $this->faker->numberBetween(1, 100),
        ];
    }
}

