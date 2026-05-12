<?php

namespace Database\Factories;

use App\Models\UserPoint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class UserPointFactory extends Factory
{
    protected $model = UserPoint::class;

    public function definition()
    {
        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addYear();
        
        return [
            'user_id' => User::factory(),
            'point' => $this->faker->numberBetween(100, 10000),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 1,
        ];
    }
}

