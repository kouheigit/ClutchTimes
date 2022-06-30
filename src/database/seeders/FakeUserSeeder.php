<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker;
use Faker\Factory;

class FakeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<10; $i++) {
            $faker = Faker\Factory::create('ja_JP');
            $value = [
                'email' => $faker->email,
                'password' => bcrypt('test7777'),
            ];
            User::insert($value);
            //DB::table('users')->insert($value);
        }
    }
}
