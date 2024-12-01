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
        $staffMembers = DB::table('v_lib_staff')->get();

        $payrollData = [];

        foreach ($staffMembers as $staff) {
            $salaryBrut = DB::select('SELECT * FROM fn_get_salary_brut(?, ?)', [$staff->id, $referenceDate])[0];
            $cnapsOstie = DB::select('SELECT * FROM fn_cnaps_and_ostie(?, ?)', [$staff->id, $referenceDate])[0];
            $revenueImposable = DB::select('SELECT fn_revenue_imposable(?, ?)', [$staff->id, $referenceDate])[0]->fn_revenue_imposable;
            $totalRetenue = DB::select('SELECT fn_total_retenue(?, ?)', [$staff->id, $referenceDate])[0]->fn_total_retenue;
            $salaryNet = DB::select('SELECT fn_salary_net(?, ?)', [$staff->id, $referenceDate])[0]->fn_salary_net;
            $netAPayer = DB::select('SELECT fn_salary_net_a_payer(?, ?)', [$staff->id, $referenceDate])[0]->fn_salary_net_a_payer;

            // absence details
            $absenceDetails = DB::table('v_detention_on_absence')
                ->where('id_staff', $staff->id)
                ->where('month_start', DB::raw("DATE_TRUNC('month', '".$referenceDate."'::date)"))
                ->first();

            // overtime details
            $overtimeDetails = DB::table('v_monthly_overtime_amount')
                ->where('id_staff', $staff->id)
                ->where('month_start', DB::raw("DATE_TRUNC('month', '".$referenceDate."'::date)"))
                ->first();            

            $payrollData[] = [
                'staff' => $staff,
                'salary_brut' => $salaryBrut,
                'cnaps_ostie' => $cnapsOstie,
                'revenue_imposable' => $revenueImposable,
                'total_retenue' => $totalRetenue,
                'salary_net' => $salaryNet,
                'net_a_payer' => $netAPayer,
                'absence_details' => $absenceDetails,
                'total_heure_sup' => $overtimeDetails
            ];
        }

        return view('payload.etat-de-paie', [
            'payrollData' => $payrollData,
            'referenceDate' => $referenceDate
        ]);
    }
}