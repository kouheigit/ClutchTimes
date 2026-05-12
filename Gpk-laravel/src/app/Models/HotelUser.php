<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelUser extends Model
{
    use HasFactory;

    protected $table = 'hotel_user';

    protected $fillable = [
        'hotel_id',
        'user_id',
    ];

    /**
     * リレーション: ホテル
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * リレーション: ユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
