<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'reservation_id', 'service_id',
        'price', 'quantity', 'total_price',
        'payment', 'payment_status', 'type', 'status',
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
     * リレーション: サービス
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * リレーション: 注文明細
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
