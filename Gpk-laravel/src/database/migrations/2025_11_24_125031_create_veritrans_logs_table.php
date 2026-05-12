<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVeritransLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('veritrans_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('reservation_id')->nullable()->constrained();
            $table->string('order_id')->comment('Veritrans注文ID');
            $table->integer('type')->default(1)->comment('タイプ');
            $table->string('txn_status')->nullable()->comment('トランザクションステータス');
            $table->string('txn_result_code')->nullable()->comment('結果コード');
            $table->text('err_message')->nullable()->comment('エラーメッセージ');
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veritrans_logs');
    }
}
