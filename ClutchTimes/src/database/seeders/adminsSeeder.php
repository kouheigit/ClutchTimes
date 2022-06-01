<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class adminsSeeder extends Seeder
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
            //'password' => Hash::make('test7777'),
        ];
        DB::table('admins')->insert($value);
    }
}
