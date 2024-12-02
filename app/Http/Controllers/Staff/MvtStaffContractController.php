<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Misc\Department;
use App\Models\Staff\MvtStaffContract;
use App\Models\Staff\Staff;
use App\Models\Staff\StaffContract;
use App\Models\Staff\StaffPosition;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MvtStaffContractController extends Controller
{
    public static $url = '/staff/{id}/contract';
    private $form_view = 'pages.back-office.mvt-staff-contract.form';

    public static function url(string $id_staff)
    { return '/staff/'.$id_staff.'/contract'; }

    public function create(string $id)
    {
        $staff = Staff::findOrFail($id);

        // check if staff has not worked in the company yet
        if($staff->d_staff_status == null && Staff::require_trial())
        {
            // only trial contract (Contrat d'Essai)
            $contracts = StaffContract::options(3);
        }
        else
        {
            $contracts = StaffContract::options();
            // remove possibility to do trial contract if staff is not in trial phase anymore
            if($staff->d_id_staff_contract != 3)
            { unset($contracts[3]); }
        }

        return view($this->form_view)->with([
            'staff' => $staff,
            'contracts' => $contracts,
            'positions' => StaffPosition::options(),
            'departments' => Department::options(),
            'form_action' => MvtStaffContractController::url($id),
            'form_method' => 'POST'
        ]);
	}

    public function store(Request $request, string $id)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'type_contrat' => 'required|exists:staff_contract,id',
            'date_entree' => 'required|date',
            'periode' => 'required_if:type_contrat,1,3|date|after:date_entree',
            'salaire_propose' => 'required|numeric|gt:0',
            'department' => 'required|exists:department,id',
            'position' => 'required|exists:staff_position,id',
        ]);

        $id_contract = $request->input('type_contrat');

        $date_min = $request->input('date_entree');
        $date_max = $request->input('periode');

        $this->do_additional_validation($staff, $id_contract, $date_min, $date_max);

        // there is no date-max if type_contract is CDI
        $date_max = $id_contract == 2 /* CDI */ ? null: $request->input('periode');

        $mvt_contract = new MvtStaffContract();
        $mvt_contract->id_staff = $id;
        $mvt_contract->id_staff_contract = $id_contract;
        $mvt_contract->id_staff_position = $request->input('position');
        $mvt_contract->id_department = $request->input('department');
        $mvt_contract->salary = $request->input('salaire_propose');
        $mvt_contract->date_start = $date_min;
        $mvt_contract->date_end = $date_max;

        $mvt_contract->save();

        return redirect(StaffController::$url)->with('success', $id_contract == 3 ? 'Contrat ajouté avec succès':'Contrat renouvelé avec succès');
    }

    private function do_additional_validation($staff, $id_contract, $date_min, $date_max)
    {
        // check contract does not intersect another existing contract
        $intersecting_contracts = MvtStaffContract::find_intersecting($staff->id, $date_min, $date_max);
        if(isset($intersecting_contracts[0]))
        {
            throw ValidationException::withMessages([
                'date_entree' => 'The selected date conflicts with an existing contract.',
            ]);
        }

        // check number of renewal is valid
        $contract = StaffContract::find($id_contract);
        if($contract->renewal_available >= 0 && $contract->renewal_available < MvtStaffContract::count_renewals($staff->id, $id_contract))
        {
            throw ValidationException::withMessages([
                'type_contrat' => 'Cannot renew anymore of this type of contract.',
            ]);
        }

        // check new contract does not exceed max_period_month if contract is not CDI
        if($id_contract != 2 && $staff->d_date_contract_start != null && $contract->max_period_month >= 0)
        {
            // this functionality has been simplified for time reasons
            $interval = (new DateTime($date_max))->diff(new DateTime($staff->d_date_contract_start));
            if ($interval->m + ($interval->y * 12) > $contract->max_period_month)
            {
                throw ValidationException::withMessages([
                    'type_contrat' => 'The quantity of activity of this staff has exced the maximum duration for this type of contract.',
                ]);
            }
        }
    }

    public function pdf($id)
    {
        // TODO do the pdf thing
        abort(404, 'This resource does not exist in this reality.');

        // Retrieve contrat_cv data with related models
        // $contratCv = Contrat_cvModel::with([
        //     'cv.dossier.besoinPoste.poste'
        // ])->findOrFail($id);

        // // Pass data to the view
        // $data = [
        //     'contratCv' => $contratCv
        // ];

        // // Load the view and render as PDF
        // $pdf = Pdf::loadView('contrat.pdf', $data);

        // // Return the PDF as download
        // return $pdf->download('contract_' . $contratCv->id . '.pdf');
    }
}
