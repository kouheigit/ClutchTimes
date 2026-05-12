<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToCalendarOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calendar_options', function (Blueprint $table) {
            // カレンダーIDとステータスの複合インデックス
            try {
                $table->index(['calendar_id', 'status'], 'idx_calendar_status');
            } catch (\Exception $e) {
                // 既に存在する場合はスキップ
            }
            
            // ソート順のインデックス
            try {
                $table->index('sort', 'idx_sort');
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
        Schema::table('calendar_options', function (Blueprint $table) {
            $table->dropIndex('idx_calendar_status');
            $table->dropIndex('idx_sort');
        });
    }
}
