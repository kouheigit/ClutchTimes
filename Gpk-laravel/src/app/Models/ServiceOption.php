<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id', 'title', 'price', 'sort', 'status',
    ];

    /**
     * リレーション: サービス
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
