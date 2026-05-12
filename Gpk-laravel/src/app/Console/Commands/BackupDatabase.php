<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--retention=30 : 保持日数}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'データベースのバックアップを実行';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('===== データベースバックアップ開始 =====');

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');

        $date = Carbon::now()->format('Ymd_His');
        $backupDir = storage_path('backups');
        
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $backupFile = $backupDir . "/db_backup_{$date}.sql.gz";

        // mysqldumpコマンドを実行
        $command = sprintf(
            'mysqldump -h %s -P %s -u %s -p%s --single-transaction --routines --triggers %s | gzip > %s',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($backupFile)
        );

        exec($command, $output, $returnVar);

        if ($returnVar === 0 && file_exists($backupFile)) {
            $fileSize = filesize($backupFile);
            $this->info("✅ バックアップ成功: {$backupFile}");
            $this->info("ファイルサイズ: " . $this->formatBytes($fileSize));

            // 古いバックアップを削除
            $retentionDays = (int) $this->option('retention');
            $this->cleanOldBackups($backupDir, $retentionDays);

            $this->info('===== バックアップ完了 =====');
            return Command::SUCCESS;
        } else {
            $this->error('❌ バックアップ失敗');
            return Command::FAILURE;
        }
    }

    /**
     * 古いバックアップファイルを削除
     */
    private function cleanOldBackups($backupDir, $retentionDays)
    {
        $files = glob($backupDir . '/db_backup_*.sql.gz');
        $cutoffDate = Carbon::now()->subDays($retentionDays);

        foreach ($files as $file) {
            if (filemtime($file) < $cutoffDate->timestamp) {
                unlink($file);
                $this->info("削除: " . basename($file));
            }
        }
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



















