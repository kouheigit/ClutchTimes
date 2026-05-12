<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VeritransLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'reservation_id', 'order_id', 'type',
        'txn_status', 'txn_result_code', 'err_message',
    ];

    /**
     * リレーション: ユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * リレーション: 予約
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
