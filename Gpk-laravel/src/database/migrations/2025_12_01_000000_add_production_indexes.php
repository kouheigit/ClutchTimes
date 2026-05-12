<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddProductionIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // reservationsテーブルのインデックス追加
        if (Schema::hasTable('reservations')) {
            Schema::table('reservations', function (Blueprint $table) {
                // インデックスが存在しない場合のみ追加（エラーを無視）
                try {
                    $table->index(['user_id', 'status'], 'idx_user_status');
                } catch (\Exception $e) {
                    // 既に存在する場合はスキップ
                }
                
                try {
                    $table->index('checkin_date', 'idx_checkin_date');
                } catch (\Exception $e) {
                    // 既に存在する場合はスキップ
                }
            });
        }
        
        // ordersテーブルのインデックス追加
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                try {
                    $table->index('reservation_id', 'idx_reservation_id');
                } catch (\Exception $e) {
                    // 既に存在する場合はスキップ
                }
            });
        }
        
        // user_pointsテーブルのインデックス追加
        if (Schema::hasTable('user_points')) {
            Schema::table('user_points', function (Blueprint $table) {
                try {
                    $table->index(['user_id', 'to'], 'idx_user_to');
                } catch (\Exception $e) {
                    // 既に存在する場合はスキップ
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex('idx_user_status');
            $table->dropIndex('idx_checkin_date');
        });
        
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex('idx_reservation_id');
            });
        }
        
        if (Schema::hasTable('user_points')) {
            Schema::table('user_points', function (Blueprint $table) {
                $table->dropIndex('idx_user_to');
            });
        }
    }
}

