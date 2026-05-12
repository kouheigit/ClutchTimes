<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Hotel;
use App\Models\User;
use App\Models\Calendar;
use App\Consts\ReservationConst;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        $checkinDate = $this->faker->dateTimeBetween('now', '+1 month');
        $checkoutDate = (clone $checkinDate)->modify('+1 day');
        $days = $checkinDate->diff($checkoutDate)->days;

        return [
            'hotel_id' => Hotel::factory(),
            'user_id' => User::factory(),
            'owner_id' => User::factory(),
            'calendar_id' => Calendar::factory(),
            'checkin_date' => $checkinDate,
            'checkout_date' => $checkoutDate,
            'days' => $days,
            'adult' => $this->faker->numberBetween(1, 4),
            'child' => $this->faker->numberBetween(0, 2),
            'dog' => $this->faker->numberBetween(0, 1),
            'name' => $this->faker->name(),
            'payment' => ReservationConst::PAYMENT_CASH,
            'status' => ReservationConst::STATUS_RESERVED,
        ];
    }
}

