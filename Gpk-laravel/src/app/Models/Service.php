<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id', 'title', 'body', 'price', 'stock', 'minimum', 'unit',
        'tab', 'sort', 'image', 'status',
    ];

    /**
     * リレーション: ホテル
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * リレーション: サービスオプション
     */
    public function serviceOptions()
    {
        return $this->hasMany(ServiceOption::class);
    }

    /**
     * リレーション: 注文
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * リレーション: カート明細
     */
    public function cartDetails()
    {
        return $this->hasMany(CartDetail::class);
    }
}
