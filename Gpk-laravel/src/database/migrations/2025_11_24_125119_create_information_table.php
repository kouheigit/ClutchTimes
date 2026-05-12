<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('タイトル');
            $table->text('body')->nullable()->comment('本文');
            $table->date('publish_date')->nullable()->comment('公開日');
            $table->integer('status')->default(1)->comment('1:公開, 0:非公開');
            $table->integer('sort')->default(0)->comment('並び順');
            $table->timestamps();
            
            $table->index(['status', 'publish_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('information');
    }
}
