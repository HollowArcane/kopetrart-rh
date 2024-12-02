<?php

namespace App\Models\Staff;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class Staff extends Model
{
    public $table = 'staff';
    public $timestamps = false;

    // true if new staff has to go through a trial contract, false otherwise
    public static function require_trial()
    { return true; }

    public static function retire_age()
    { return 60; }


    public static function options()
    {
        return DB::table('staff')
                    ->selectRaw("id, CONCAT(first_name, ' ', last_name) AS name")
                    ->pluck('name', 'id');
    }

    public static function lib_active()
    { return DB::table('v_lib_staff_active'); }

    public static function candidates()
    { return DB::table('staff')->where('d_staff_status', null)->get(); }

    public static function lib(array $where)
    { return DB::table('v_lib_staff')->where($where); }

    public static function format_seniority($staff, string $year, string $month, string $day): string
    {
        $seniority = (new DateTime())->diff(new DateTime($staff->d_date_contract_start));
        $format = '';
        if($seniority->y > 0)
        { $format .= "$seniority->y $year" . ($seniority->y > 1 ? 's': ' '); }

        if($seniority->m > 0)
        { $format .= "$seniority->m $month"; }

        if($seniority->d > 0)
        { $format .= "$seniority->d $day" . ($seniority->d > 1 ? 's': ''); }

        return $format;
    }

    public static function get_or_create(string $first_name, string|null $last_name, string $email, string $date_birth): Staff|stdClass
    {
        $staff = DB::table('staff')
                    ->where('first_name', $first_name)
                    ->where('last_name', $last_name)
                    ->first();
        if($staff == null)
        {
            $staff = new Staff();
            $staff->first_name = $first_name;
            $staff->last_name = $last_name;
            $staff->date_birth = $date_birth;
            $staff->email = $email;

            $staff->save();
        }
        return $staff;
    }
}
