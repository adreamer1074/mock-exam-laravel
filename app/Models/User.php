<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // ユーザー認証のためにAuthenticatableクラスをインポート
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Notifications\Notifiable; // 通知機能
use Laravel\Sanctum\HasApiTokens; // APIトークン管理

/**
 * Userモデルはアプリケーションのユーザーを表し、Laravelの認証システムに対応する
 * Authenticatableクラスを拡張しています。
 */
class User extends Authenticatable // 認証機能を利用するためにAuthenticatableを拡張
{
    use SoftDeletes; 
    use HasApiTokens; 
    use Notifiable; 

    /**
     * 複数代入可能な属性。
     * 
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'role', 'favorites'];

    /**
     * Examモデルとの一対多のリレーションを定義します。
     * 1人のユーザーは複数の試験を持つことができます。
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exams()
    {
        return $this->hasMany(Exam::class); // ユーザーは複数の試験を作成可能
    }

    /**
     * TodoListモデルとの一対多のリレーションを定義します。
     * 1人のユーザーは複数のToDoリストを持つことができます。
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function todoLists()
    {
        return $this->hasMany(TodoList::class); // ユーザーは複数のToDoリストを作成可能
    }

    /**
     * "favorites"テーブルを介したExamモデルとの多対多のリレーションを定義します。
     * ユーザーがお気に入りに追加した試験を表します。
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteExams()
    {
        return $this->belongsToMany(Exam::class, 'favorites'); // ユーザーは複数のお気に入り試験を持つ
    }
}
