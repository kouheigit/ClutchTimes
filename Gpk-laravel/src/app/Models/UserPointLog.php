<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPointLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'point', 'reason', 'type',
    ];

    /**
     * リレーション: ユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
