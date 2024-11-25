<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Department extends Model
{
    public $table = 'department';
    public $timestamps = false;

    public static function options()
    { return DB::table('department')->pluck('label', 'id'); }
}
