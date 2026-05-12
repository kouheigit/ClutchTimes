<?php

namespace Database\Factories;

use App\Models\Holiday;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class HolidayFactory extends Factory
{
    protected $model = Holiday::class;

    public function definition()
    {
        return [
            'date' => Carbon::now()->addDays($this->faker->numberBetween(1, 365)),
            'name' => $this->faker->randomElement(['元日', '成人の日', '建国記念の日', '春分の日', '昭和の日', '憲法記念日', 'みどりの日', 'こどもの日', '海の日', '山の日', '敬老の日', '秋分の日', 'スポーツの日', '文化の日', '勤労感謝の日']),
        ];
    }
}

