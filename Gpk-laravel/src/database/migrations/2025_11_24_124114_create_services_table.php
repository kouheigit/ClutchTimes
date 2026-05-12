<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained();
            $table->string('title')->comment('サービス名');
            $table->text('body')->nullable()->comment('説明');
            $table->integer('price')->default(0)->comment('価格');
            $table->integer('stock')->default(0)->comment('在庫数（0は無制限）');
            $table->integer('minimum')->default(1)->comment('最小注文数');
            $table->string('unit')->default('個')->comment('単位');
            $table->integer('tab')->default(1)->comment('1:事前予約, 2:現地注文');
            $table->integer('sort')->default(0)->comment('並び順');
            $table->string('image')->nullable()->comment('画像');
            $table->integer('status')->default(1)->comment('1:有効, 0:無効');
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
        Schema::dropIfExists('services');
    }
}
