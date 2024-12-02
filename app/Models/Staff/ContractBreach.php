<?php

namespace App\Models\Staff;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContractBreach extends Model
{
    public $table = 'contract_breach';
    public $timestamps = false;

    use HasFactory;

    public static function read_details()
    {
        return DB::table('v_staff_contract_breach')
                        ->where('is_validated', '=', false)
                        ->unionAll(DB::table('v_staff_contract_breach')
                                        ->where('is_validated', '=', true));
    }

    public static function read_current_contract_breach(string $id_staff)
    {
        return DB::table('contract_breach', 'cb')
                    ->join('mvt_staff_contract AS msc', 'cb.id_mvt_staff_contract', '=', 'msc.id')
                    ->where('msc.id_staff', '=', $id_staff)
                    ->where('cb.is_validated', '=', false)
                    ->first();
    }

    public static function read_date_notice(string $id_staff, string $date)
    {
        $row = DB::selectOne('SELECT * FROM fn_staff_contract_breach_notice_date(?, ?)', [$id_staff, $date]);
        if($row->duration == '00:00:00')
        { $row->duration = null; }
        return $row;
    }

    public static function read_notice_salary_bonus(string $today, string $date_expected, string $id_contract_breach_type, string $id_staff): float
    {
        $row = DB::selectOne('SELECT  f_b_salary_bonus(?, ?, ?, ?) AS bonus', [$today, $date_expected, $id_contract_breach_type, $id_staff]);
        return $row->bonus ?? 0;
    }
    public static function read_contract_salary_bonus(string $date_expected, object $staff): float
    {
        if($staff->d_id_staff_contract == 2) // CDI
        {
            return ContractBreach::read_CDI_salary_bonus($date_expected, $staff);
        }
        else if($staff->d_id_staff_contract == 1) // CDD
        {
            return ContractBreach::read_CDD_salary_bonus($staff);
        }
        return 0;
    }

    public static function read_CDD_salary_bonus(object $staff)
    {
        $contract_dates = DB::table('staff', 's')
                ->join('mvt_staff_contract AS mvs', 's.d_id_mvt_staff_contract', '=', 'mvs.id')
                ->select('date_start', 'date_end')
                ->where('s.id', $staff->id)
                ->first();

        $interval = (new DateTime($contract_dates->date_end))->diff(new DateTime($contract_dates->date_start));
        return ($interval->y * 12 + $interval->m)  * $staff->d_salary * .1;
    }

    public static function read_CDI_salary_bonus(string $date_expected, object $staff)
    {
        $contract_dates = DB::table('staff', 's')
                ->join('mvt_staff_contract AS mvs', 's.d_id_mvt_staff_contract', '=', 'mvs.id')
                ->select('date_start', 'date_end')
                ->where('s.id', $staff->id)
                ->first();

        $year = (new DateTime($date_expected))->diff(new DateTime($contract_dates->date_start))->y;
        if($year <= 10)
        { return $year * $staff->d_salary / 4; }
        return 10 * $staff->salary/4 + ($year - 10) * $staff->d_salary/3;
    }
}
