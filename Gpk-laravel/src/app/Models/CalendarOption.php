<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'calendar_id', 'title', 'body', 'sort', 'status',
    ];

    /**
     * リレーション: カレンダー
     */
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }
}
