<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id', 'service_id', 'service_option_id',
        'price', 'quantity', 'total_price',
    ];

    /**
     * リレーション: カート
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
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
