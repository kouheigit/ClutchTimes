<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleaseLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('release_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('action')->comment('アクション');
            $table->text('data')->nullable()->comment('データ（JSON）');
            $table->timestamps();
            
            $table->index(['calendar_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('release_logs');
    }
}
