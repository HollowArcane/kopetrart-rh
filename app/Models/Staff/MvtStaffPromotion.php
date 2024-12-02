<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MvtStaffPromotion extends Model
{
    use HasFactory;

    public $table = 'mvt_staff_promotion';
    public $timestamps = false;

    public static function read_lib()
    {
        return DB::table('v_lib_mvt_staff_promotion');
    }
}
