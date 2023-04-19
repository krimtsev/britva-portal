<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'password',
        'role_id',
        'is_disabled',
        'last_auth'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $roleList = [
        'USER' => [
            'KEY' => 1,
            'VALUE' => 'Пользователь'
        ],
        'ADMIN' => [
            'KEY' => 2,
            'VALUE' => 'Администратор'
        ]
    ];

    public function roleListById()
    {
        return array_reduce(array_values($this->roleList), function ($acc, $item) {
            $acc[$item['KEY']] = $item['VALUE'];
            return $acc;
        }, []);
    }

    public function userRole()
    {

        $array = $this->roleListById();

        if (array_key_exists($this->role_id, $array)) {
            return $array[$this->role_id];
        }

        return false;
    }

    public function isAdmin()
    {

        return $this->role_id == $this->roleList['ADMIN']['KEY'];
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'user_id', 'id');
    }

    public function sheets()
    {
        return $this->hasMany(Sheet::class, 'user_id', 'id');
    }

    public function digests()
    {
        return $this->hasMany(Digest::class, 'user_id', 'id');
    }
}
