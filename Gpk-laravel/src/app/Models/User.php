<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_id', 'name', 'email', 'password',
        'last_name', 'first_name', 'last_kana', 'first_kana',
        'zip1', 'zip2', 'address1', 'address2', 'tel',
        'company_name', 'company_kana', 'company_zip1', 'company_zip2',
        'company_address1', 'company_address2', 'company_tel', 'company_fax',
        'send_name', 'send_kana', 'send_zip1', 'send_zip2',
        'send_address1', 'send_address2', 'send_tel',
        'type', 'agree', 'status', 'user_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * リレーション: ホテル（多対多）
     */
    public function hotels()
    {
        return $this->belongsToMany(Hotel::class);
    }

    /**
     * リレーション: 予約
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * リレーション: 注文
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * リレーション: ポイント
     */
    public function userPoints()
    {
        return $this->hasMany(UserPoint::class);
    }

    /**
     * リレーション: フリーデイ
     */
    public function freedays()
    {
        return $this->hasMany(Freeday::class);
    }

    /**
     * リレーション: カレンダー
     */
    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }
}
