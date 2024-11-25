<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StaffPosition extends Model
{
    public $table = 'staff_position';
    public $timestamps = false;

    public static function options()
    { return DB::table('staff_position')->pluck('label', 'id'); }
}
