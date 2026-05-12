<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Calendar;
use App\Models\Freeday;
use App\Models\Service;
use App\Models\ServiceOption;
use App\Models\Information;
use App\Consts\UserConst;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 既存のテストデータを削除
        User::where('email', 'owner@test.com')->orWhere('email', 'user@test.com')->delete();
        Hotel::where('name', '空ノ庭 軽井沢')->delete();
        
        // テストユーザー（オーナー）作成
        $owner = User::firstOrCreate(
            ['email' => 'owner@test.com'],
            [
                'name' => 'テストオーナー',
                'password' => bcrypt('password'),
                'type' => UserConst::TYPE_OWNER,
                'status' => 1,
            ]
        );
        
        // テストユーザー（一般）作成
        $user = User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'テストユーザー',
                'password' => bcrypt('password'),
                'type' => UserConst::TYPE_GENERAL,
                'status' => 1,
            ]
        );
        
        // ホテル作成
        $hotel = Hotel::firstOrCreate(
            ['name' => '空ノ庭 軽井沢'],
            [
                'address' => '長野県北佐久郡御代田町馬瀬口2039-2',
                'description' => '会員制宿泊施設',
                'status' => 1,
            ]
        );
        
        // オーナーとホテルを関連付け（重複チェック）
        if (!$owner->hotels()->where('hotel_id', $hotel->id)->exists()) {
            $owner->hotels()->attach($hotel->id);
        }
        
        // カレンダー（FIXDAY）作成 - 今後12ヶ月分（既存のものはスキップ）
        $startDate = Carbon::now()->firstOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $monthStart = $startDate->copy()->addMonths($i)->firstOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            
            Calendar::firstOrCreate(
                [
                    'hotel_id' => $hotel->id,
                    'user_id' => $owner->id,
                    'start_date' => $monthStart,
                    'end_date' => $monthEnd,
                ],
                [
                    'date' => $monthStart,
                    'status' => 1, // 予約可
                ]
            );
        }
        
        // フリーデイ作成（既存のものはスキップ）
        Freeday::firstOrCreate(
            [
                'user_id' => $owner->id,
                'start_date' => Carbon::now()->subMonths(12)->format('Y-m-d'),
                'end_date' => Carbon::now()->addYear()->format('Y-m-d'),
            ],
            [
                'freedays' => 10,
                'status' => 1,
            ]
        );
        
        // サービス作成
        $service1 = Service::create([
            'hotel_id' => $hotel->id,
            'title' => '朝食セット',
            'body' => '和食・洋食からお選びいただけます',
            'price' => 1500,
            'stock' => 100,
            'minimum' => 1,
            'unit' => '人前',
            'tab' => 1, // 事前予約タブ
            'sort' => 1,
            'status' => 1,
        ]);
        
        $service2 = Service::create([
            'hotel_id' => $hotel->id,
            'title' => '夕食セット',
            'body' => '季節の食材を使ったコース料理',
            'price' => 5000,
            'stock' => 50,
            'minimum' => 1,
            'unit' => '人前',
            'tab' => 1,
            'sort' => 2,
            'status' => 1,
        ]);
        
        // サービスオプション作成
        ServiceOption::create([
            'service_id' => $service1->id,
            'title' => '和食',
            'price' => 0,
            'sort' => 1,
            'status' => 1,
        ]);
        
        ServiceOption::create([
            'service_id' => $service1->id,
            'title' => '洋食',
            'price' => 200,
            'sort' => 2,
            'status' => 1,
        ]);
        
        // お知らせ作成
        Information::create([
            'title' => 'システムメンテナンスのお知らせ',
            'body' => 'システムメンテナンスを実施いたします。ご不便をおかけして申し訳ございません。',
            'publish_date' => Carbon::now(),
            'status' => 1,
            'sort' => 1,
        ]);
        
        $this->command->info('テストデータを作成しました！');
        $this->command->info('オーナー: owner@test.com / password');
        $this->command->info('一般ユーザー: user@test.com / password');
    }
}

