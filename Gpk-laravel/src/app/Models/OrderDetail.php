<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'service_id', 'service_option_id',
        'price', 'quantity', 'total_price',
    ];

    /**
     * リレーション: 注文
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * リレーション: サービス
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * リレーション: サービスオプション
     */
    public function serviceOption()
    {
        return $this->belongsTo(ServiceOption::class);
    }
}
