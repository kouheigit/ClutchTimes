<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('reservation_id')->constrained();
            $table->integer('total_price')->default(0)->comment('合計金額');
            $table->integer('payment')->default(0)->comment('0:現地払い, 1:クレジット');
            $table->integer('payment_status')->default(0)->comment('0:未払い, 1:支払済み');
            $table->integer('status')->default(1)->comment('ステータス');
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['reservation_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('add_orders');
    }
}
