<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calendars', function (Blueprint $table) {
            // 追加のインデックス（既存のインデックスと重複しないように）
            if (!Schema::hasColumn('calendars', 'status')) {
                return;
            }
            
            // ステータスと日付の複合インデックス
            try {
                $table->index(['status', 'start_date'], 'idx_status_start_date');
            } catch (\Exception $e) {
                // 既に存在する場合はスキップ
            }
            
            // 日付範囲検索用のインデックス
            try {
                $table->index('end_date', 'idx_end_date');
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
        Schema::table('calendars', function (Blueprint $table) {
            $table->dropIndex('idx_status_start_date');
            $table->dropIndex('idx_end_date');
        });
    }
}
