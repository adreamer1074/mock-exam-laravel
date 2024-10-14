<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class);
    }

        // Fillable propertyにoption_textを追加
    protected $fillable = [
        'option_text',
        'is_correct',
        // 他の属性があればここに追加
    ];
}
