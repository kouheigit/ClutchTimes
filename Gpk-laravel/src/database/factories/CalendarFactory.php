<?php

namespace Database\Factories;

use App\Models\Calendar;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalendarFactory extends Factory
{
    protected $model = Calendar::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 year');
        $endDate = (clone $startDate)->modify('+1 day');

        return [
            'hotel_id' => Hotel::factory(),
            'user_id' => User::factory(),
            'date' => $startDate,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 1, // 1:予約可
        ];
    }
}

