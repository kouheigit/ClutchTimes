<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->date('date')->comment('対象日');
            $table->date('start_date')->nullable()->comment('期間開始日');
            $table->date('end_date')->nullable()->comment('期間終了日');
            $table->integer('status')->default(1)->comment('1:予約可, 2:予約中, 3:予約済, 9:休業');
            $table->timestamps();
            
            // インデックス
            $table->index(['hotel_id', 'date']);
            $table->index(['user_id', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendars');
    }
}
