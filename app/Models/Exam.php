<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'user_id', 'is_published', 'category_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function category()
    {
        return $this->belongsTo(ExamCategory::class);
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function favoriteByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}