<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // データベースバックアップ（毎日午前2時）
        $schedule->command('backup:database')
            ->dailyAt('02:00')
            ->onFailure(function () {
                // バックアップ失敗時の通知（必要に応じて実装）
                \Log::error('データベースバックアップが失敗しました');
            });

        // ログクリーンアップ（毎週日曜日午前3時）
        $schedule->command('log:clear')
            ->weeklyOn(0, '03:00')
            ->when(function () {
                return config('logging.channels.daily.days', 14) > 0;
            });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
