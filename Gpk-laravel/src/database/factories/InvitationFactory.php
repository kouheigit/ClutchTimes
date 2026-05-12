<?php

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvitationFactory extends Factory
{
    protected $model = Invitation::class;

    public function definition()
    {
        return [
            'reservation_id' => Reservation::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'token' => \Illuminate\Support\Str::random(32),
            'status' => $this->faker->randomElement([0, 1]), // 0: 未登録, 1: 登録済
        ];
    }
}

