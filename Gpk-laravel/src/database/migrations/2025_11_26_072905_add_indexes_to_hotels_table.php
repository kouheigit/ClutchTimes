<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            // ステータスのインデックス
            try {
                $table->index('status', 'idx_status');
            } catch (\Exception $e) {
                // 既に存在する場合はスキップ
            }
            
            // 名前のインデックス（検索用）
            try {
                $table->index('name', 'idx_name');
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
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropIndex('idx_status');
            $table->dropIndex('idx_name');
        });
    }
}
