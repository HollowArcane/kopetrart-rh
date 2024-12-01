<?php

namespace App\Http\Controllers\Payroll;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Log;

class PayrollController extends Controller
{
    public function index()
    {
        return view('payload.etat-de-paie');
    }

    public function generate_payroll(Request $request)
    {
        $referenceDate = $request->input('date-reference') ?? now()->format('Y-m-d');

        // Fetch all staff members
        $staffMembers = DB::table('v_lib_staff')->get();

        $payrollData = [];

        foreach ($staffMembers as $staff) {
            // Use the SQL functions to calculate payroll details
            $salaryBrut = DB::select('SELECT * FROM fn_get_salary_brut(?, ?)', [$staff->id, $referenceDate])[0];
            $cnapsOstie = DB::select('SELECT * FROM fn_cnaps_and_ostie(?, ?)', [$staff->id, $referenceDate])[0];
            $revenueImposable = DB::select('SELECT fn_revenue_imposable(?, ?)', [$staff->id, $referenceDate])[0]->fn_revenue_imposable;
            $totalRetenue = DB::select('SELECT fn_total_retenue(?, ?)', [$staff->id, $referenceDate])[0]->fn_total_retenue;
            $salaryNet = DB::select('SELECT fn_salary_net(?, ?)', [$staff->id, $referenceDate])[0]->fn_salary_net;
            $netAPayer = DB::select('SELECT fn_salary_net_a_payer(?, ?)', [$staff->id, $referenceDate])[0]->fn_salary_net_a_payer;

            // Fetch absence details
            $absenceDetails = DB::table('v_detention_on_absence')
                ->where('id_staff', $staff->id)
                ->where('month_start', DB::raw("DATE_TRUNC('month', '".$referenceDate."'::date)"))
                ->first();

            // Fetch Heure Sup Majorée (Monthly Overtime Amount)
            $monthlyOvertime = DB::table('v_monthly_overtime_amount')
                ->where('id_staff', $staff->id)
                ->where('month_start', DB::raw("DATE_TRUNC('month', '".$referenceDate."'::date)"))
                ->first();

            // Fetch Indemnité (Staff Compensation)
            $compensation = DB::table('staff_compensation')
                ->where('id_staff', $staff->id)
                ->whereRaw("DATE_TRUNC('month', date_compensation) = DATE_TRUNC('month', '".$referenceDate."'::date)")
                ->sum('amount');

            // Fetch Impot Du
            $impotDu = DB::table('impot_due')
                ->where('id_staff', $staff->id)
                ->whereRaw("DATE_TRUNC('month', date_due) = DATE_TRUNC('month', '".$referenceDate."'::date)")
                ->sum('amount');

            // Fetch Avance
            $avance = DB::table('salary_advance')
                ->where('id_staff', $staff->id)
                ->whereRaw("DATE_TRUNC('month', date_advance) = DATE_TRUNC('month', '".$referenceDate."'::date)")
                ->sum('amount');

            $payrollData[] = [
                'staff' => $staff,
                'salary_brut' => $salaryBrut,
                'cnaps_ostie' => $cnapsOstie,
                'revenue_imposable' => $revenueImposable,
                'total_retenue' => $totalRetenue,
                'salary_net' => $salaryNet,
                'net_a_payer' => $netAPayer,
                'absence_details' => $absenceDetails,
                'monthly_overtime' => $monthlyOvertime,
                'compensation' => $compensation,
                'impot_du' => $impotDu,
                'avance' => $avance
            ];
        }

        return view('payload.etat-de-paie', [
            'payrollData' => $payrollData,
            'referenceDate' => $referenceDate
        ]);
    }
}