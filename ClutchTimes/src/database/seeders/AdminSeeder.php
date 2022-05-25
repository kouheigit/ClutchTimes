<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $value = [
            'name' => 'admin',
            'email' => 'ookawa@teconet.co.jp',
            'password'=> bcrypt('test7777'),
        ];
        DB::table('admins')->insert($value);
    }
}
