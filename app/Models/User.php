<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
