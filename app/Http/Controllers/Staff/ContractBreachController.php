<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff\ContractBreach;
use App\Models\Staff\Staff;
use App\Models\Staff\StaffPositionTask;
use App\Models\Staff\StaffVacation;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ContractBreachController extends Controller
{
    public static $url = '/staff/{id}/contract-breach';
    private $index_view = 'pages.back-office.contract-breach.index';
    private $form_view = 'pages.back-office.contract-breach.form';
    private $pdf_view = 'pages.back-office.contract-breach.pdf';
    private $pdf_detail_view = 'pages.back-office.contract-breach.pdf-general';

    public static function url(string $id_staff)
    { return '/staff/'.$id_staff.'/contract-breach'; }


    public function index()
    {
        if(session('role') != 1 /* PDG */ && session('role') != 2 /* RH */ && session('role') != 3 /* RE */)
        {
            return abort(400, 'Invalid operation');
        }
        $staffs = ContractBreach::read_details()
                    // ->where('is_validated', '=', false)
                    ->get();

        return view($this->index_view)->with('staffs', $staffs);
    }

    public function create(string $id, string $type)
    {
        $staff = $this->check_staff_contract_breachable($id);
        if(
            session('role') != 1 /* PDG */          && session('role') != 3 /* RE */
         || $staff->d_staff_status == 2 /* Inactif */
         || $type == 2 /* Licenciement */           && session('role') != 1 /* PDG */
         || $type == 1 /* Demission */              && session('role') != 3 /* RE */
         || $type == 3 /* Mise à la Retraite */     && (new \DateTime())->diff(new \DateTime($staff->date_birth))->y < Staff::retire_age()
         || $type == 4 /* Rupture Conventionelle */ && $staff->d_id_staff_contract != 2 /* CDI */
        )
        {
            return abort(400, 'Invalid operation');
        }
        return view($this->form_view)->with([
            'staff' => $staff,
            'type' => $type,
            'notice' => ContractBreach::read_date_notice($staff->id, (new DateTime())->format('Y-m-d')),
            'form_action' => ContractBreachController::url($id),
            'form_method' => 'POST'
        ]);
    }

    public function store(string $id, Request $request)
    {
        $staff = $this->check_staff_contract_breachable($id);
        $type = $request->input('id-contract-breach-type');

        if(
            session('role') != 1 /* PDG */          && session('role') != 3 /* RE */
         || $staff->d_staff_status == 2 /* Inactif */
         || $type == 2 /* Licenciement */           && session('role') != 1 /* PDG */
         || $type == 1 /* Demission */              && session('role') != 3 /* RE */
         || $type == 3 /* Mise à la Retraite */     && (new \DateTime())->diff(new \DateTime($staff->date_birth))->y < Staff::retire_age()
         || $type == 4 /* Rupture Conventionelle */ && $staff->d_id_staff_contract != 2 /* CDI */
        )
        {
            return abort(400, 'Invalid operation');
        }

        $request->validate([
            'id-contract-breach-type' => 'required|exists:contract_breach_type,id',
            'date-source' => 'required|date',
            'date-expected' => 'required|date|after:date-source',
            'comment' => 'required|string',
            'salary' => 'required|numeric'
        ]);

        $salary = $request->input('salary');
        $salary_min = array_sum($this->salary_bonus($staff->id, $request->input('id-contract-breach-type'), $request->input('date-source'), $request->input('date-expected'), $request->input('comment-status')));

        if($salary < $salary_min)
        {
            throw ValidationException::withMessages([
                'salary' => 'Salary must be at least ' . $salary_min
            ]);
        }

        $id_contract_breach_type = $request->input('id-contract-breach-type');
        $contract_breach = new ContractBreach();
        $contract_breach->id_mvt_staff_contract = $staff->d_id_mvt_staff_contract;
        $contract_breach->id_contract_breach_type = $request->input('id-contract-breach-type');
        $contract_breach->date_source = $request->input('date-source');
        $contract_breach->date_validated = $request->input('date-expected');
        $contract_breach->salary_bonus = $id_contract_breach_type == 1 ? -$salary: $salary;
        $contract_breach->comment = $request->input('comment');
        $contract_breach->id_role = session('role');

        $contract_breach->save();
        return redirect('/staff')->with('success', 'Rupture de contrat communiquée avec succès');
    }

    public function delete(string $id)
    {
        $contract_breach = ContractBreach::find($id);
        if($contract_breach->is_validated || session('role') != $contract_breach->id_role)
        {
            return abort(400, 'Invalid operation');
        }

        ContractBreach::destroy($id);
        return redirect('/staff')->with('success', 'Rupture de contrat annulée avec succès');
    }

    public function accept(string $id)
    {
        $contract_breach = ContractBreach::find($id);
        if(session('role') != $contract_breach->id_role)
        {
            if(in_array(session('role'), [1 /* PDG */, 3 /* RE */]) && $contract_breach->date_target == null)
            {
                $contract_breach->date_target = (new DateTime())->format('Y-m-d');
            }
            else if(session('role') == 2 /* RH */ && $contract_breach->is_validated == false)
            {
                $contract_breach->is_validated = true;
            }
            else
            {
                return abort(400, 'Invalid operation');
            }
        }
        else
        {
            return abort(400, 'Invalid operation');
        }

        $contract_breach->save();

        return redirect('/contract-breach')->with('success', 'Rupture de contrat validée avec succès');
    }

    public function check_staff_contract_breachable($id_staff)
    {
        $staff = Staff::lib_active()->where('id', $id_staff)->first();
        $crt_contract_breach = ContractBreach::read_current_contract_breach($staff->id);
        if($staff == null)
        { abort(404, 'Resource not found'); }

        if($crt_contract_breach != null)
        { abort(400, 'Employee cannot have another contract breach before the current one is resolved'); }

        return $staff;
    }

    public function salary_bonus($id_staff, $id_contract_breach_type, $today, $date_expected, $comment_status)
    {
        if(!in_array(session('role'), [1 /* PDG */, 3 /* RE */]))
        { abort(404, 'Resource not found'); }

        $staff = Staff::find($id_staff);
        $salaries = [
            'salary_notice' => ContractBreach::read_notice_salary_bonus($today, $date_expected, $id_contract_breach_type, $staff->id),
            'salary_contract' => ContractBreach::read_contract_salary_bonus($date_expected, $staff),
            'salary_vacation' => StaffVacation::read_vacation_salary_bonus($today, $staff)
        ];

        if($comment_status == 'danger' /* Motif Grave */ || $id_contract_breach_type == 1 /* Démission */)
        { $salaries['salary_contract'] = 0; }

        return $salaries;
    }

    public function pdf($id)
    {
        if(!in_array(session('role'), [1 /* PDF */, 2 /* RH */, 3 /* RE */]))
        {
            return abort(404, 'Resource not found');
        }

        $staff = ContractBreach::read_details()
                    ->where('id', '=', $id)
                    ->first();

        if($staff == null)
        {
            abort(404, 'Resource not found');
        }

        $pdf = Pdf::loadView($this->pdf_view, [
            'staff' => $staff,
            'tasks' => StaffPositionTask::read_by_position($staff->d_id_staff_position)->get()
        ]);
        return $pdf->stream('certificat_contract.pdf');
    }

    public function pdf_detail($id)
    {
        if(!in_array(session('role'), [1 /* PDF */, 2 /* RH */, 3 /* RE */]))
        {
            return abort(404, 'Resource not found');
        }

        $staff = ContractBreach::read_details()
                    ->where('id', '=', $id)
                    ->first();

        if($staff == null)
        {
            abort(404, 'Resource not found');
        }

        $months = [
            '01' => 'Janvier',
            '02' => 'Février',
            '03' => 'Mars',
            '04' => 'Avril',
            '05' => 'Mai',
            '06' => 'Juin',
            '07' => 'Juillet',
            '08' => 'Août',
            '09' => 'Septembre',
            '10' => 'Octobre',
            '11' => 'Novembre',
            '12' => 'Décembre'
        ];

        $today = new \DateTime($staff->date_validated);
        $salaries = [];
        for($i = 0; $i < 12; $i++)
        {
            $date = $today->sub(new \DateInterval('P1M'));
            $date_format = $months[$date->format('m')].' '.$date->format('Y');
            $salaries[$date_format] = DB::select('SELECT * FROM fn_get_salary_brut(?, ?)', [$staff->id_staff, $date->format('Y-m-d')])[0]?->res_monthly_gross_salary;
        }
        $salaries = array_reverse($salaries);

        $pdf = Pdf::loadView($this->pdf_detail_view, [
            'staff' => $staff,
            'salaries' => $salaries
        ]);
        return $pdf->stream('attestation_pole_emploi.pdf');
    }

    public function pdf_payment($id, $today)
    {
        session(['ref_date' => $today]);
        return redirect('/payroll/export-pdf/'.$id);
    }
}
