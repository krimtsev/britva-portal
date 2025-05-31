<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Audit extends Model
{
    use HasFactory;

    protected $table = 'audit';

    protected $fillable = [
        'type',
        'new',
        'old',
        'login',
        'user_id',
        'role_id'
    ];

    static $types = [
        'staff' => 'staff',
        'ticket' => 'ticket'
    ];

    static function normalize($data): string
    {
        if (!$data) return "";
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    protected static function isValidAuditData(array $data): bool
    {
        return isset($data['type'], $data['new'])
            && in_array($data['type'], self::$types);
    }

    public static function add(array $data): void {
        if (!self::isValidAuditData($data)) {
            return;
        }

        $user = Auth::user();
        if ($user) {
            $data['login'] = $user->login ?? null;
            $data['user_id'] = $user->id ?? null;
            $data['role_id'] = $user->role_id ?? null;
        }

        $data['new'] = self::normalize($data['new']);
        $data['old'] = self::normalize($data['old']);

        Audit::create($data);
    }
}
