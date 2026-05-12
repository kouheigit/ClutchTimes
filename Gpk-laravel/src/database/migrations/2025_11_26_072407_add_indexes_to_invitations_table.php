<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invitations', function (Blueprint $table) {
            // オーナーIDとステータスの複合インデックス
            try {
                $table->index(['owner_id', 'status'], 'idx_owner_status');
            } catch (\Exception $e) {
                // 既に存在する場合はスキップ
            }
            
            // トークンのインデックス（既にunique制約があるが、検索用に追加）
            try {
                $table->index('token', 'idx_token');
            } catch (\Exception $e) {
                // 既に存在する場合はスキップ
            }
            
            // メールアドレスのインデックス
            try {
                $table->index('email', 'idx_email');
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
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropIndex('idx_owner_status');
            $table->dropIndex('idx_token');
            $table->dropIndex('idx_email');
        });
    }
}
