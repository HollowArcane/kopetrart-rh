<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class LoginModel extends Model
{
    protected $table = 'admin';

    protected $primaryKey = 'id_admin';

    protected $fillable = ['username', 'password', 'id_profil'];

    public $timestamps = false;

    public static function authenticate($username, $password)
    {
        $user = self::where('username', $username)
                    ->first();

        if ($user) {
            return $user;
        }

        return false;
    }
}

