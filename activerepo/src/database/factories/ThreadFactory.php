<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thread>
 */
class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('ja_JP');
        return [
            'title'=>'今日の出来事',
            'name'=>'鈴木涼介',
            'comment'=>'春の訪れを感じる今日この頃、暖かな日差しが心地よい。桜のつぼみも膨らみ始め、そろそろ花見の季節がやってくる。新しいことに挑戦したくなるこの時期、何か小さな目標を立ててみるのも良いかもしれない。',
            'generateid'=>'1240e2e200',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
