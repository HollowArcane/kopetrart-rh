<?php

namespace App\Http\Controllers\Staff;

use App\Models\Staff\StaffCompensation;
use App\Models\Staff\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class StaffCompensationController extends Controller
{
    public function index()
    {
        $compensations = StaffCompensation::with('staff')->paginate(10);
        return view('staff_compensations.index', compact('compensations'));
    }

    public function create()
    {
        $staffs = Staff::all();
        return view('staff_compensations.form', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_staff' => 'required|exists:staff,id',
            'motif' => 'required|string|max:255',
            'date_compensation' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($request) {
            StaffCompensation::create($request->all());
        });

        return redirect()->route('staff_compensations.index')
            ->with('success', 'Compensation record created successfully.');
    }

    public function edit(StaffCompensation $staffCompensation)
    {
        $staffs = Staff::all();
        return view('staff_compensations.form', compact('staffCompensation', 'staffs'));
    }

    public function update(Request $request, StaffCompensation $staffCompensation)
    {
        $request->validate([
            'id_staff' => 'required|exists:staff,id',
            'motif' => 'required|string|max:255',
            'date_compensation' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($request, $staffCompensation) {
            $staffCompensation->update($request->all());
        });

        return redirect()->route('staff_compensations.index')
            ->with('success', 'Compensation record updated successfully.');
    }

    public function destroy(StaffCompensation $staffCompensation)
    {
        DB::transaction(function () use ($staffCompensation) {
            $staffCompensation->delete();
        });

        return redirect()->route('staff_compensations.index')
            ->with('success', 'Compensation record deleted successfully.');
    }
}   