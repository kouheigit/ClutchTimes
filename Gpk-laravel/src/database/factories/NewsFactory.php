<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class NewsFactory extends Factory
{
    protected $model = News::class;

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

