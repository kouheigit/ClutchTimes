<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmpOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'service_id', 'service_option_id',
        'price', 'quantity', 'total_price', 'type',
    ];

    /**
     * リレーション: ユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
