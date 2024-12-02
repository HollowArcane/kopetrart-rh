<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff\ContractBreach;
use App\Models\Staff\Staff;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public static $url = '/staff';

    private $list_view = 'pages.back-office.staff.index';
    private $show_view = 'pages.back-office.staff.show';
    private $candidate_view = 'pages.back-office.staff.candidate';

	public function index()
    {
		$staffs = Staff::lib_active()->get();
        return view($this->list_view)->with([
            'staffs' => $staffs,
        ]);
    }

    public function candidate()
    {
        $candidates = Staff::candidates();
        return view($this->candidate_view)->with('staffs', $candidates);
    }

    public function show(string $id)
    {
        $staff = Staff::lib_active()->where('id', $id)->first();
        if($staff == null)
        { abort(400, 'Resource not found'); }

        return view($this->show_view)->with([
            'staff' => $staff,
            'can_breach' => ContractBreach::read_current_contract_breach($staff->id) == null
        ]);
    }
}
