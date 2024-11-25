<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MvtStaffContract extends Model
{
    public $table = 'mvt_staff_contract';
    public $timestamps = false;

    public static function find_intersecting(string $id_staff, string $date_min, string|null $date_max)
    {
        $op1 = '<';
        if($date_max == null)
        {
            $date_max = $date_min;
            $op1 = '<=';
        }

        return DB::table('mvt_staff_contract')
                ->where('id_staff', $id_staff)
                ->where('date_start', $op1, $date_max)
                ->whereRaw("COALESCE(date_end, date_start) > ?", $date_min)
                ->get();
    }

    public static function count_renewals(string $id_staff, string $id_contract)
    {
        return DB::table('mvt_staff_contract')
                    ->selectRaw('COUNT(*) AS count')
                    ->where('id_staff', $id_staff)
                    ->where('id_staff_contract', $id_contract)
                    ->first()->count;
    }
}
