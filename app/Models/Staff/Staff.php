<?php

namespace App\Models\Staff;

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

    public static function lib_active()
    { return DB::table('v_lib_staff_active'); }

    public static function candidates()
    { return DB::table('staff')->where('d_staff_status', null)->get(); }

    public static function lib(array $where)
    { return DB::table('v_lib_staff')->where($where); }

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
