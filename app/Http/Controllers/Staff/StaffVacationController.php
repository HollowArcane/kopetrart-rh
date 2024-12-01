<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff\Staff;
use App\Models\Staff\StaffVacation;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StaffVacationController extends Controller
{
    public static $url = '/staff-vacation';

    private $list_view = 'pages.back-office.staff-vacation.index';
    private $form_view = 'pages.back-office.staff-vacation.form';

    public function index()
    {
        if(!in_array(session('role'), [3 /* RE */, 1 /* PDG */]))
        {
            abort(400, 'Invalid Operation');
        }
        return view($this->list_view)->with('staffs', StaffVacation::lib());
    }

    public function create()
    {
        if(session('role') != 3 /* RE */)
        {
            return abort(400, 'Invalid operation');
        }
        $staffs = Staff::options();
        return view($this->form_view)->with([
            'form_method' => 'POST',
            'form_action' => StaffVacationController::$url,
            'staffs' => $staffs,
            'vacations' => StaffVacation::read_staff_vacation_status((new DateTime())->format('Y-m-d'), $staffs->keys()[0])
        ]);
    }

    public function store(Request $request)
    {
        if(session('role') != 3 /* RE */)
        {
            return abort(400, 'Invalid operation');
        }

        $request->validate([
            'date-start' => 'required|date',
            'date-end' => 'required|date|after_or_equal:date-start',
            'id-staff' => 'required|exists:staff,id'
        ]);

        $this->do_additional_validation($request);

        $staff_vacation = new StaffVacation();
        $staff_vacation->date_start = $request->input('date-start');
        $staff_vacation->date_end = $request->input('date-end');
        $staff_vacation->id_staff = $request->input('id-staff');

        $staff_vacation->save();
        return redirect('/staff-vacation')->with('success', 'Demande de Congé ajouté avec succès');
    }

    public function accept(string $id)
    {
        $staff_vacation = StaffVacation::find($id);
        if(session('role') != 1 /* PDG */ || $staff_vacation->date_validated != null)
        {
            return abort(400, 'Invalid operation');
        }

        $staff_vacation->date_validated = (new DateTime())->format('Y-m-d');
        $staff_vacation->save();

        return redirect('/staff-vacation')->with('success', 'Demande de Congé acceptée avec succès');
    }

    private function do_additional_validation(Request $request)
    {
        $date_start = new DateTime($request->input('date-start'));
        $date_end = new DateTime($request->input('date-end'));
        $id_staff = $request->input('id-staff');

        // check vacation does not intersect another existing vacation
        $intersecting_vacations = StaffVacation::find_intersecting($id_staff, $date_start->format('Y-m-d'), $date_end->format('Y-m-d'));
        if(isset($intersecting_vacations[0]))
        {
            throw ValidationException::withMessages([
                'date-start' => 'The selected date conflicts with an existing vacation.',
            ]);
        }

        $vacation_available = $this->vacation_available(
            $id_staff,
            $request->input('date-start')
        );
        if($date_end->diff($date_start)->days + 1 > $vacation_available->day_vacation_left)
        {
            throw ValidationException::withMessages([
                'date-start' => 'Duration of vacation exceeds maximum allowed'
            ]);
        }
    }

    public function delete(string $id)
    {
        $staff_vacation = StaffVacation::find($id);
        if(! in_array(session('role'), [1 /* PDG */, 3 /* RE */]) || $staff_vacation->date_validated != null)
        {
            return abort(400, 'Invalid operation');
        }

        $staff_vacation->delete();
        return redirect('/staff-vacation')->with('success', 'Demande de Congé annulé avec succès');
    }

    public function vacation_available($id, $today)
    {
        return StaffVacation::read_staff_vacation_status($today, $id);
    }
}
