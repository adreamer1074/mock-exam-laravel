<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    public function question()
    {
        return $this->belongsTo(ExamQuestion::class);
    }
}
