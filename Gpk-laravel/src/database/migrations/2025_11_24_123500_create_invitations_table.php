<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id')->comment('予約ID');
            $table->foreignId('owner_id')->constrained('users')->comment('オーナー');
            $table->foreignId('user_id')->nullable()->constrained('users')->comment('ゲストユーザー');
            $table->string('token')->unique()->comment('招待トークン');
            $table->string('name')->comment('招待される人の名前');
            $table->string('email')->comment('招待される人のメール');
            $table->integer('status')->default(1)->comment('1:未登録, 2:登録済み');
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
        Schema::dropIfExists('invitations');
    }
}
