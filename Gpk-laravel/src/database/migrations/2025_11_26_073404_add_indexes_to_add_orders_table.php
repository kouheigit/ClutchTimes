<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToAddOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('add_orders', function (Blueprint $table) {
            // 支払いステータスのインデックス
            try {
                $table->index('payment_status', 'idx_payment_status');
            } catch (\Exception $e) {
                // 既に存在する場合はスキップ
            }
            
            // 支払い方法のインデックス
            try {
                $table->index('payment', 'idx_payment');
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
        Schema::table('add_orders', function (Blueprint $table) {
            $table->dropIndex('idx_payment_status');
            $table->dropIndex('idx_payment');
        });
    }
}
