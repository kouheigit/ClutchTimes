<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'add_order_id', 'service_id', 'service_option_id',
        'price', 'quantity', 'total_price',
    ];

    /**
     * リレーション: 追加注文
     */
    public function addOrder()
    {
        return $this->belongsTo(AddOrder::class);
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
