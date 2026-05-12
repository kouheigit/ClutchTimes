<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToFreedaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('freedays', function (Blueprint $table) {
            // ステータスと開始日の複合インデックス
            try {
                $table->index(['status', 'start_date'], 'idx_status_start_date');
            } catch (\Exception $e) {
                // 既に存在する場合はスキップ
            }
            
            // 有効期限のインデックス（既存のuser_id_end_dateインデックスと併用）
            try {
                $table->index('start_date', 'idx_start_date');
            } catch (\Exception $e) {
                // 既に存在する場合はスキップ
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('freedays', function (Blueprint $table) {
            $table->dropIndex('idx_status_start_date');
            $table->dropIndex('idx_start_date');
        });
    }
}
