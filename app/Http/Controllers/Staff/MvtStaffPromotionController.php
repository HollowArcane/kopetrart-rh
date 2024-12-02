<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Misc\Department;
use App\Models\Staff\MvtStaffContract;
use App\Models\Staff\MvtStaffPromotion;
use App\Models\Staff\Staff;
use App\Models\Staff\StaffPosition;
use App\Models\Staff\StaffPromotion;
use Illuminate\Http\Request;

class MvtStaffPromotionController extends Controller
{
    private string $index_view = 'pages.back-office.mvt-staff-promotion.index';
    private string $form_view = 'pages.back-office.mvt-staff-promotion.form';

    public function index()
    {
        return view($this->index_view)->with('staffs', MvtStaffPromotion::read_lib()->get());
    }

    public function create(string $id_staff)
    {
        $staff = Staff::findOrFail($id_staff);
        if(!in_array(session('role'), [1 /* PDG */]))
        {
            abort(400, 'Invalid operation');
        }

        return view($this->form_view)->with([
            'form_method' => 'POST',
            'form_action' => '/staff-promotion/'.$id_staff,
            'staff' => $staff,
            'departments' => Department::options(),
            'positions' => StaffPosition::options()
        ]);
    }

    public function store(Request $request, string $id_staff)
    {
        $staff = Staff::findOrFail($id_staff);
        if(!in_array(session('role'), [1 /* PDG */]) || $staff->d_staff_status != 1 /* Actif */)
        {
            abort(400, 'Invalid operation');
        }

        $request->validate([
            'id-staff-position' => 'required|exists:staff_position,id',
            'id-department' => 'required|exists:department,id',
            'salary' => 'required|numeric|gt:0',
            'date' => 'required|date'
        ]);

        $promotion = new MvtStaffPromotion();
        $promotion->id_staff = $id_staff;
        $promotion->id_staff_position = $request->input('id-staff-position');
        $promotion->id_department = $request->input('id-department');
        $promotion->salary = $request->input('salary');
        $promotion->date = $request->input('date');

        $promotion->save();

        return redirect('/staff')->with('success', 'Demande de Promotion ajoutée avec succès');
    }
}
