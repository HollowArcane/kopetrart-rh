<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StaffContract extends Model
{
    public $table = 'staff_contract';
    public $timestamps = false;
    public static function options($id_contract = null)
    {
        if($id_contract != null)
        { return DB::table('staff_contract')->where('id', $id_contract)->pluck('label', 'id'); }

        return DB::table('staff_contract')->pluck('label', 'id');
    }
}
