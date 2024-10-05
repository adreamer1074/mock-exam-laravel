<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'role', 'favorites'];

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function todoLists()
    {
        return $this->hasMany(TodoList::class);
    }

    public function favoriteExams()
    {
        return $this->belongsToMany(Exam::class, 'favorites');
    }
}