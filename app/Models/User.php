<?php

namespace App\Models;

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
        'name',
        'password',
        'role_id',
        'is_disabled',
        'last_activity',
        'partner_id'
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

    public function nameOrLogin() {
        if ($this->name) return $this->name;
        return $this->login;
    }

    protected $roleList = [
        'USER' => [
            'ID' => 1,
            'NAME' => 'Пользователь',
            'VALUE' => 1000
        ],
        'SYS_ADMIN' => [
            'ID' => 2,
            'NAME' => 'Системный администратор',
            'VALUE' => 3000
        ],
        'ADMIN' => [
            'ID' => 3,
            'NAME' => 'Администратор',
            'VALUE' => 2000
        ],
    ];

    public function accessValueByRoleId(int $id)
    {
        switch ($id) {
            case $this->roleList['USER']['ID']:
                return $this->roleList['USER']['VALUE'];
            case $this->roleList['ADMIN']['ID']:
                return $this->roleList['ADMIN']['VALUE'];
            case $this->roleList['SYS_ADMIN']['ID']:
                return $this->roleList['SYS_ADMIN']['VALUE'];
            default:
                return 0;
        }
    }

    public function accessValueByRoleName(string $key)
    {
        if (array_key_exists($key, $this->roleList)) {
            return $this->roleList[$key]['VALUE'];
        }
        return 0;
    }

    public function roleListById()
    {
        return array_reduce(array_values($this->roleList), function ($acc, $item) {
            $acc[$item['ID']] = $item['NAME'];
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

    public function checkAccessRoleValue($value) {
        return self::accessValueByRoleId($this->role_id) >= $value;
    }

    public function isSysAdmin(): bool
    {
        return $this->role_id == $this->roleList['SYS_ADMIN']['ID'];
    }

    public function isAdmin(): bool
    {
        return $this->role_id == $this->roleList['ADMIN']['ID'];
    }

    public function isUser()
    {
        return $this->role_id == $this->roleList['USER']['ID'];
    }

    public function isAdminOrSysAdmin(): bool
    {
        return $this->isAdmin() || $this->isSysAdmin();
    }

    public function isAccessRightAdminOrHigher() {
        return self::checkAccessRoleValue(self::accessValueByRoleName('ADMIN'));
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
