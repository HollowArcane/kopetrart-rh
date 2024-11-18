<?php

namespace App\Models\FrontOffice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Message extends Model
{
    use HasFactory;

    protected $connection = 'pgsql2';

    public $table = 'message';
    public $timestamps = false;

    public static function get($id)
    {
        return DB::connection('pgsql2')
                    ->table('v_message')
                    ->where('id_login_sender', $id)
                    ->orWhere('id_login_target', $id)
                    ->orderBy('created_at')
                    ->get();
    }
}
