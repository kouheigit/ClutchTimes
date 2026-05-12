<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'reservation_id', 'total_price',
        'payment', 'payment_status', 'status',
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

    /**
     * リレーション: 追加注文明細
     */
    public function addOrderDetails()
    {
        return $this->hasMany(AddOrderDetail::class);
    }
}
