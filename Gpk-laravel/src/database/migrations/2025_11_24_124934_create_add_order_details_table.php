<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('add_order_id')->constrained('add_orders')->onDelete('cascade');
            $table->foreignId('service_id')->constrained();
            $table->foreignId('service_option_id')->nullable()->constrained('service_options');
            $table->integer('price')->default(0)->comment('単価');
            $table->integer('quantity')->default(1)->comment('数量');
            $table->integer('total_price')->default(0)->comment('合計金額');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('add_order_details');
    }
}
