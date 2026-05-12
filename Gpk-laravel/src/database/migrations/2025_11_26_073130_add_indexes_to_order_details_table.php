<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            // サービスIDとサービスオプションIDの複合インデックス
            try {
                $table->index(['service_id', 'service_option_id'], 'idx_service_option');
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
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropIndex('idx_service_option');
        });
    }
}
