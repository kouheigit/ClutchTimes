<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id', 'user_id', 'date', 'start_date', 'end_date', 'status',
    ];

    protected $casts = [
        'date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
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

    /**
     * リレーション: 予約
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * リレーション: カレンダーオプション
     */
    public function calendarOptions()
    {
        return $this->hasMany(CalendarOption::class);
    }
}
