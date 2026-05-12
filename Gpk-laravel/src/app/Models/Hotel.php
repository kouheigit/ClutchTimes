<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'description', 'status',
    ];

    /**
     * リレーション: ユーザー（多対多）
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * リレーション: 予約
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * リレーション: カレンダー
     */
    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }

    /**
     * リレーション: サービス
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
