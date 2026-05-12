<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ClearOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear {--days=14 : 保持日数}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '古いログファイルを削除';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $logPath = storage_path('logs');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("===== ログクリーンアップ開始（{$days}日以上前のログを削除） =====");

        $files = File::glob($logPath . '/*.log*');
        $deletedCount = 0;
        $totalSize = 0;

        foreach ($files as $file) {
            $fileTime = Carbon::createFromTimestamp(filemtime($file));
            
            if ($fileTime->lt($cutoffDate)) {
                $fileSize = filesize($file);
                File::delete($file);
                $deletedCount++;
                $totalSize += $fileSize;
                $this->info("削除: " . basename($file) . " ({$this->formatBytes($fileSize)})");
            }
        }

        if ($deletedCount > 0) {
            $this->info("✅ {$deletedCount}個のファイルを削除しました（合計: {$this->formatBytes($totalSize)}）");
        } else {
            $this->info("削除対象のファイルはありませんでした");
        }

        $this->info('===== ログクリーンアップ完了 =====');
        return Command::SUCCESS;
    }

    /**
     * バイト数を人間が読める形式に変換
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}



















