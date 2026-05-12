<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id', 'user_id', 'action', 'data',
    ];

    /**
     * リレーション: 予約
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * リレーション: ユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
