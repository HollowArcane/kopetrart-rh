<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    use HasFactory;

    public $table = 'notification';
    public $timestamps = false;

    public static function get_relevant($id_role)
    {
        return DB::table('notification AS n')
                    ->leftJoin('notification_seen AS nn', 'n.id', '=', 'nn.id_notification')
                    ->where('n.id_role', '=', $id_role)
                    ->select('n.id', 'n.title', 'n.message', 'n.redirection', 'n.datetime', 'n.id_role', 'nn.id_admin', 'nn.date_seen')
                    ->orderBy('datetime', 'desc')
                    ->get();
    }

    public static function get($id_role, $id_notification)
    {
        return DB::table('notification AS n')
                    ->leftJoin('notification_seen AS nn', 'n.id', '=', 'nn.id_notification')
                    ->where('n.id_role', '=', $id_role)
                    ->where('n.id', '=', $id_notification)
                    ->select('n.id', 'n.title', 'n.message', 'n.redirection', 'n.datetime', 'n.id_role', 'nn.id_admin', 'nn.date_seen')
                    ->first();
    }
}
