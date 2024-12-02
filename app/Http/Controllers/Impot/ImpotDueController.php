<?php

namespace App\Http\Controllers\Impot;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Impot\ImpotDue;
use App\Models\Staff\Staff;

class ImpotDueController extends Controller
{
    public function index()
    {
        $impotDues = ImpotDue::with('staff')->get();
        return view('impot_dues.index', compact('impotDues'));
    }

    public function create()
    {
        $staffs = Staff::all();
        return view('impot_dues.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_staff' => 'required|exists:staff,id',
            'date_due' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
        ]);

        ImpotDue::create($request->all());
        return redirect()->route('impot_dues.index')->with('success', 'Impot Due record created successfully.');
    }

    public function edit(ImpotDue $impotDue)
    {
        $staffs = Staff::all();
        return view('impot_dues.edit', compact('impotDue', 'staffs'));
    }

    public function update(Request $request, ImpotDue $impotDue)
    {
        $request->validate([
            'id_staff' => 'required|exists:staff,id',
            'date_due' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $impotDue->update($request->all());
        return redirect()->route('impot_dues.index')->with('success', 'Impot Due record updated successfully.');
    }

    public function destroy(ImpotDue $impotDue)
    {
        $impotDue->delete();
        return redirect()->route('impot_dues.index')->with('success', 'Impot Due record deleted successfully.');
    }
}