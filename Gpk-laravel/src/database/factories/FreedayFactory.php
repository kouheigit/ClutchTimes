<?php

namespace Database\Factories;

use App\Models\Freeday;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class FreedayFactory extends Factory
{
    protected $model = Freeday::class;

    public function definition()
    {
        $startDate = Carbon::now()->addMonths($this->faker->numberBetween(1, 6));
        $endDate = $startDate->copy()->addYear();
        
        return [
            'user_id' => User::factory(),
            'freedays' => $this->faker->numberBetween(1, 10),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 1,
        ];
    }
}

