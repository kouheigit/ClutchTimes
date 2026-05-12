<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_id', 'user_id', 'owner_id', 'calendar_id', 'invitation_id',
        'checkin_date', 'checkout_date', 'checkin_time', 'checkout_time', 'days',
        'name', 'adult', 'child', 'dog', 'note',
        'room_key', 'upload',
        'payment', 'status',
    ];

    protected $casts = [
        'checkin_date' => 'date',
        'checkout_date' => 'date',
        'checkin_time' => 'datetime',
        'checkout_time' => 'datetime',
    ];

    /**
     * リレーション: ホテル
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * リレーション: ユーザー（予約者）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * リレーション: オーナー
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * リレーション: カレンダー
     */
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    /**
     * リレーション: 招待
     */
    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }

    /**
     * リレーション: 注文
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * リレーション: 追加注文
     */
    public function addOrders()
    {
        return $this->hasMany(AddOrder::class);
    }

    /**
     * リレーション: 予約ログ
     */
    public function reservationLogs()
    {
        return $this->hasMany(ReservationLog::class);
    }

    /**
     * 最新の予約を取得（静的メソッド）
     */
    public static function getLastReservation($user_id = null)
    {
        $query = self::with(['hotel', 'user', 'calendar'])
            ->orderBy('checkin_date', 'desc');
        
        if ($user_id) {
            $query->where('owner_id', $user_id);
        }
        
        return $query->first();
    }
}
