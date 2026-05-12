<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id', 'owner_id', 'user_id', 'token', 'name', 'email', 'status',
    ];

    /**
     * リレーション: 予約
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * リレーション: オーナー
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * リレーション: ゲストユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
