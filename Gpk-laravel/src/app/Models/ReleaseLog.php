<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleaseLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'calendar_id', 'user_id', 'action', 'data',
    ];

    /**
     * リレーション: カレンダー
     */
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    /**
     * リレーション: ユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
