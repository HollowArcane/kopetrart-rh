<?php

namespace App\Http\Controllers\Advance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Advance\SalaryAdvance;
use App\Models\Staff\Staff;

class SalaryAdvanceController extends Controller
{
    public function index()
    {
        $salaryAdvances = SalaryAdvance::with('staff')->get();
        return view('salary_advances.index', compact('salaryAdvances'));
    }

    public function create()
    {
        $staffs = Staff::all();
        return view('salary_advances.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_staff' => 'required|exists:staff,id',
            'date_advance' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
        ]);

        SalaryAdvance::create($request->all());

        return redirect()->route('salary_advances.index')->with('success', 'Salary advance record created successfully.');
    }

    public function edit(SalaryAdvance $salaryAdvance)
    {
        $staffs = Staff::all();
        return view('salary_advances.edit', compact('salaryAdvance', 'staffs'));
    }

    public function update(Request $request, SalaryAdvance $salaryAdvance)
    {
        $request->validate([
            'id_staff' => 'required|exists:staff,id',
            'date_advance' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $salaryAdvance->update($request->all());

        return redirect()->route('salary_advances.index')->with('success', 'Salary advance record updated successfully.');
    }

    public function destroy(SalaryAdvance $salaryAdvance)
    {
        $salaryAdvance->delete();

        return redirect()->route('salary_advances.index')->with('success', 'Salary advance record deleted successfully.');
    }
}