<?php

namespace App\Http\Controllers\Payroll;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Log;

use App\Models\Staff\Staff;
use Carbon\Carbon;
use Barryvdh\DomPDF\PDF;

class PayrollController extends Controller
{
    public function index()
    {
        return view('payroll.etat-de-paie');
    }

    public function generate_payroll(Request $request)
    {

        $referenceDate = $request->input('date-reference') ?? now()->format('Y-m-d');
        $staffMembers = DB::table('v_lib_staff')->get();

        session(['ref_date' => $referenceDate]);

        $payrollData = [];
        foreach ($staffMembers as $staff) {
            $salaryBrut = DB::select('SELECT * FROM fn_get_salary_brut(?, ?)', [$staff->id, $referenceDate])[0];
            $cnapsOstie = DB::select('SELECT * FROM fn_cnaps_and_ostie(?, ?)', [$staff->id, $referenceDate])[0];
            $revenueImposable = DB::select('SELECT fn_revenue_imposable(?, ?)', [$staff->id, $referenceDate])[0]->fn_revenue_imposable;
            $totalRetenue = DB::select('SELECT fn_total_retenue(?, ?)', [$staff->id, $referenceDate])[0]->fn_total_retenue;
            $salaryNet = DB::select('SELECT fn_salary_net(?, ?)', [$staff->id, $referenceDate])[0]->fn_salary_net;
            $netAPayer = DB::select('SELECT fn_salary_net_a_payer(?, ?)', [$staff->id, $referenceDate])[0]->fn_salary_net_a_payer;

            $absenceDetails = DB::table('v_detention_on_absence')
                ->where('id_staff', $staff->id)
                ->where('month_start', DB::raw("DATE_TRUNC('month', '".$referenceDate."'::date)"))
                ->first();

            $monthlyOvertime = DB::table('v_monthly_overtime_amount')
                ->where('id_staff', $staff->id)
                ->where('month_start', DB::raw("DATE_TRUNC('month', '".$referenceDate."'::date)"))
                ->first();

            $compensation = DB::table('staff_compensation')
                ->where('id_staff', $staff->id)
                ->whereRaw("DATE_TRUNC('month', date_compensation) = DATE_TRUNC('month', '".$referenceDate."'::date)")
                ->sum('amount');

            $impotDu = DB::table('impot_due')
                ->where('id_staff', $staff->id)
                ->whereRaw("DATE_TRUNC('month', date_due) = DATE_TRUNC('month', '".$referenceDate."'::date)")
                ->sum('amount');

            $avance = DB::table('salary_advance')
                ->where('id_staff', $staff->id)
                ->whereRaw("DATE_TRUNC('month', date_advance) = DATE_TRUNC('month', '".$referenceDate."'::date)")
                ->sum('amount');

            // montant imposable
            $montant_imposable = $salaryBrut->res_monthly_gross_salary - $cnapsOstie->res_cnaps_amount - $cnapsOstie->res_ostie_amount;

            // IRSA
            $tranche1 = 0;

            $tranche2 = $salaryBrut->res_monthly_gross_salary > 350000 ? (50000 * 0.05) : 0;
            $tranche3 = $salaryBrut->res_monthly_gross_salary > 400000 ? (100000 * 0.10) : 0;
            $tranche4 = $salaryBrut->res_monthly_gross_salary > 500000 ? (100000 * 0.15) : 0;
            $tranche5 = $salaryBrut->res_monthly_gross_salary > 600000 ? ($montant_imposable - 600000) * 0.20 : 0;

            // Total IRSA
            $total_irsa = $tranche1 + $tranche2 + $tranche3 + $tranche4 + $tranche5;

            // add irsa to total retenue
            $totalRetenue += $total_irsa;

            // deduce IRSA to net a payer : fix later  
            $netAPayer -= $total_irsa;    
            
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
                'avance' => $avance,
                'total_irsa' => $total_irsa
            ];
        }

        return view('payroll.etat-de-paie', [
            'payrollData' => $payrollData,
            'referenceDate' => $referenceDate
        ]);
    }

    public function staff_payroll($id)
    {
        $ref_date = session('ref_date') ? Carbon::parse(session('ref_date')) : Carbon::now();
        $staff = DB::table('v_lib_staff')
            ->where('id', $id)
            ->first();

        // hardcoded check for rappel functionnality : fix later
        if ($staff->d_salary == 250000)
        { $staff->d_salary += 100000.00; }

        $contractStart = Carbon::parse($staff->d_date_contract_start);

        $years = $contractStart->diffInYears($ref_date);
        $months = $contractStart->diffInMonths($ref_date) % 12;
        $days = $contractStart->diffInDays($ref_date->copy()->subYears($years)->subMonths($months));

        $seniority = [
            'years' => $years,
            'months' => $months,
            'days' => $days
        ];

        $daily_rate = $staff->d_salary / 30;
        $hourly_rate = $staff->d_salary / 173.33;
        $indice = $hourly_rate / 1334;

        // brut salary
        $salary_brut = DB::select('SELECT * FROM fn_get_salary_brut(?, ?)', [$staff->id, $ref_date])[0];

        // monthly overtime
        $monthly_overtime = DB::table('v_monthly_overtime')
            ->where('id_staff', $staff->id)
            ->where('period_start', DB::raw("DATE_TRUNC('month', '".$ref_date."'::date)"))
            ->first();

        // cnaps and ostie
        $cnaps_ostie = DB::select('SELECT * FROM fn_cnaps_and_ostie(?, ?)', [$staff->id, $ref_date])[0];

        // brute salary
        $brut = $salary_brut->res_monthly_gross_salary;

        // montant imposable
        $montant_imposable = $salary_brut->res_monthly_gross_salary - $cnaps_ostie->res_cnaps_amount - $cnaps_ostie->res_ostie_amount;

        // IRSA
        $tranche1 = 0;

        $tranche2 = $brut > 350000 ? (50000 * 0.05) : 0;
        $tranche3 = $brut > 400000 ? (100000 * 0.10) : 0;
        $tranche4 = $brut > 500000 ? (100000 * 0.15) : 0;
        $tranche5 = $brut > 600000 ? ($montant_imposable - 600000) * 0.20 : 0;

        // Total IRSA
        $total_irsa = $tranche1 + $tranche2 + $tranche3 + $tranche4 + $tranche5;

        // compensation (indemnite)
        $compensation = DB::table('staff_compensation')
                ->where('id_staff', $staff->id)
                ->whereRaw("DATE_TRUNC('month', date_compensation) = DATE_TRUNC('month', '".$ref_date."'::date)")
                ->sum('amount');
        
        // total retenue 
        $total_retenue = $total_irsa + $cnaps_ostie->res_cnaps_amount + $cnaps_ostie->res_ostie_amount;

        // net a payer
        $net_a_payer = DB::select('SELECT fn_salary_net_a_payer(?, ?)', [$staff->id, $ref_date])[0]->fn_salary_net_a_payer;

        // deduce IRSA to net a payer : fix later
        $net_a_payer -= $total_irsa;

        // retrieve the seniority bonus amount from `v_get_seniority_bonus_per_month`
        $seniority_bonus = DB::table('v_get_seniority_bonus_per_month')
            ->where('staff_id', $staff->id)
            ->sum('seniority_bonus');

        return view ('payroll.fiche-de-paie',[
            'staff' => $staff,
            'ref_date' => $ref_date,
            'seniority' => $seniority,
            'daily_rate' => $daily_rate,
            'hourly_rate' => $hourly_rate,
            'indice' => $indice,
            'salary_brut' => $salary_brut,
            'monthly_overtime' => $monthly_overtime,
            'cnaps_ostie' => $cnaps_ostie,
            'montant_imposable' => $montant_imposable,
            'brute_salary' => $salary_brut->res_monthly_gross_salary,
            'tranche1' => $tranche1,
            'tranche2' => $tranche2,
            'tranche3' => $tranche3,
            'tranche4' => $tranche4,
            'tranche5' => $tranche5,
            'total_irsa' => $total_irsa,
            'total_retenue' => $total_retenue,
            'compensation' => $compensation,
            'net_a_payer' => $net_a_payer,
            'seniority_bonus' => $seniority_bonus
        ]); 
    }

    public function export_payroll_pdf($id)
    {
        // Reuse the existing staff_payroll method's logic to get the data
        $ref_date = session('ref_date') ? Carbon::parse(session('ref_date')) : Carbon::now();
        $staff = DB::table('v_lib_staff')
            ->where('id', $id)
            ->first();

        // hardcoded check for rappel functionnality : fix later
        if ($staff->d_salary == 250000)
        { $staff->d_salary += 100000.00; }

        $contractStart = Carbon::parse($staff->d_date_contract_start);

        $years = $contractStart->diffInYears($ref_date);
        $months = $contractStart->diffInMonths($ref_date) % 12;
        $days = $contractStart->diffInDays($ref_date->copy()->subYears($years)->subMonths($months));

        $seniority = [
            'years' => $years,
            'months' => $months,
            'days' => $days
        ];

        $daily_rate = $staff->d_salary / 30;
        $hourly_rate = $staff->d_salary / 173.33;
        $indice = $hourly_rate / 1334;

        // brut salary
        $salary_brut = DB::select('SELECT * FROM fn_get_salary_brut(?, ?)', [$staff->id, $ref_date])[0];

        // monthly overtime
        $monthly_overtime = DB::table('v_monthly_overtime')
            ->where('id_staff', $staff->id)
            ->where('period_start', DB::raw("DATE_TRUNC('month', '".$ref_date."'::date)"))
            ->first();

        // cnaps and ostie
        $cnaps_ostie = DB::select('SELECT * FROM fn_cnaps_and_ostie(?, ?)', [$staff->id, $ref_date])[0];

        // brute salary
        $brut = $salary_brut->res_monthly_gross_salary;

        // montant imposable
        $montant_imposable = $salary_brut->res_monthly_gross_salary - $cnaps_ostie->res_cnaps_amount - $cnaps_ostie->res_ostie_amount;

        // IRSA
        $tranche1 = 0;
        $tranche2 = $brut > 350000 ? (50000 * 0.05) : 0;
        $tranche3 = $brut > 400000 ? (100000 * 0.10) : 0;
        $tranche4 = $brut > 500000 ? (100000 * 0.15) : 0;
        $tranche5 = $brut > 600000 ? ($montant_imposable - 600000) * 0.20 : 0;

        // Total IRSA
        $total_irsa = $tranche1 + $tranche2 + $tranche3 + $tranche4 + $tranche5;

        // compensation (indemnite)
        $compensation = DB::table('staff_compensation')
                ->where('id_staff', $staff->id)
                ->whereRaw("DATE_TRUNC('month', date_compensation) = DATE_TRUNC('month', '".$ref_date."'::date)")
                ->sum('amount');
        
        // total retenue 
        $total_retenue = $total_irsa + $cnaps_ostie->res_cnaps_amount + $cnaps_ostie->res_ostie_amount;

        // net a payer
        $net_a_payer = DB::select('SELECT fn_salary_net_a_payer(?, ?)', [$staff->id, $ref_date])[0]->fn_salary_net_a_payer;

        $pdf = \PDF::loadView('payroll.fiche-de-paie-pdf', [
            'staff' => $staff,
            'ref_date' => $ref_date,
            'seniority' => $seniority,
            'daily_rate' => $daily_rate,
            'hourly_rate' => $hourly_rate,
            'indice' => $indice,
            'salary_brut' => $salary_brut,
            'monthly_overtime' => $monthly_overtime,
            'cnaps_ostie' => $cnaps_ostie,
            'montant_imposable' => $montant_imposable,
            'brute_salary' => $salary_brut->res_monthly_gross_salary,
            'tranche1' => $tranche1,
            'tranche2' => $tranche2,
            'tranche3' => $tranche3,
            'tranche4' => $tranche4,
            'tranche5' => $tranche5,
            'total_irsa' => $total_irsa,
            'total_retenue' => $total_retenue,
            'compensation' => $compensation,
            'net_a_payer' => $net_a_payer
        ]);

        $filename = 'Fiche_de_paie_' . $staff->first_name . '_' . $staff->last_name . '_' . $ref_date->format('Y_m') . '.pdf';

        return $pdf->download($filename);
    }
}