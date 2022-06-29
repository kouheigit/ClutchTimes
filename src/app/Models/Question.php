<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    public function user_answers()
    {
        return $this->hasMany(UserAnswer::class, 'question_id', 'id');
    }
}
