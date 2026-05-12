<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            // ホテルIDとステータスの複合インデックス
            try {
                $table->index(['hotel_id', 'status', 'tab'], 'idx_hotel_status_tab');
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
        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex('idx_hotel_status_tab');
            $table->dropIndex('idx_sort');
        });
    }
}
