<?php 

namespace App\Http\Controllers\Absence;

use App\Models\Absence\Absence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Staff\Staff;

class AbsenceController extends Controller
{
    public function index()
    {
        $absences = Absence::with('staff')->get();
        return view('absences.index', compact('absences'));
    }

    public function create()
    {
        $staffs = Staff::all(); 
        return view('absences.create', compact('staffs'));
    }

    public function edit(Absence $absence)
    {
        $staffs = Staff::all(); 
        return view('absences.edit', compact('absence', 'staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_staff' => 'required|exists:staff,id',
            'number_day_absence' => 'required|numeric|min:0.5',
            'date_absence' => 'required|date',
        ]);

        Absence::create($request->all());
        return redirect()->route('absences.index')->with('success', 'Absence created successfully.');
    }

    public function update(Request $request, Absence $absence)
    {
        $request->validate([
            'id_staff' => 'required|exists:staff,id',
            'number_day_absence' => 'required|numeric|min:0.5',
            'date_absence' => 'required|date',
        ]);

        $absence->update($request->all());
        return redirect()->route('absences.index')->with('success', 'Absence updated successfully.');
    }

    public function destroy(Absence $absence)
    {
        $absence->delete();
        return redirect()->route('absences.index')->with('success', 'Absence deleted successfully.');
    }
}
