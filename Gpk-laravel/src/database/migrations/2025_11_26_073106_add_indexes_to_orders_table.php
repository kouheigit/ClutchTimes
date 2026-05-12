<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // サービスIDとステータスの複合インデックス
            try {
                $table->index(['service_id', 'status'], 'idx_service_status');
            } catch (\Exception $e) {
                // 既に存在する場合はスキップ
            }
            
            // 支払いステータスのインデックス
            try {
                $table->index('payment_status', 'idx_payment_status');
            } catch (\Exception $e) {
                // 既に存在する場合はスキップ
            }
            
            // タイプのインデックス
            try {
                $table->index('type', 'idx_type');
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_service_status');
            $table->dropIndex('idx_payment_status');
            $table->dropIndex('idx_type');
        });
    }
}
