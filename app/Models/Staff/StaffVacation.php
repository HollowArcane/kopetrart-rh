<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StaffVacation extends Model
{
    use HasFactory;

    public $table = 'staff_vacation';
    public $timestamps = false;

    public static function find_intersecting(string $id_staff, string $date_min, string|null $date_max)
    {
        return DB::table('staff_vacation')
                ->where('id_staff', $id_staff)
                ->where('date_start', '<', $date_max)
                ->where('date_end', '>', $date_min)
                ->get();
    }

    public static function lib()
    {
        return DB::table('v_lib_staff_vacation')
                        ->where('date_validated', null)
                        ->orderBy('date_start')
                        ->unionAll(
                            DB::table('v_lib_staff_vacation')
                                ->where('date_validated', '!=', null)
                                ->orderBy('date_start')
                        )->get();
    }

    public static function read_staff_vacation_status(string $today, string $id_staff)
    { return DB::selectOne('SELECT * FROM fn_staff_vacation_status(?) WHERE id_staff=?', [$today, $id_staff]); }

    public static function read_vacation_salary_bonus(string $today, object $staff): float
    {
        $row = DB::selectOne('SELECT * FROM fn_staff_vacation_status(?) WHERE id_staff=?', [$today, $staff->id]);
        if($row == null)
        { return 0; }

        return max($row->day_vacation_left * $staff->d_salary / 30, 0);
    }
}
