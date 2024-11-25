<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff\Staff;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public static $url = '/staff';

    private $list_view = 'pages.back-office.staff.index';
    private $candidate_view = 'pages.back-office.staff.candidate';

	public function index()
    {
		$staffs = Staff::lib_active()->get();
        return view($this->list_view)->with('staffs', $staffs);
    }

    public function candidate()
    {
        $candidates = Staff::candidates();
        return view($this->candidate_view)->with('staffs', $candidates);
    }
}
