<?php

namespace App\Http\Controllers\Bonus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Staff\Staff;
use App\Models\Bonus\PerformanceBonus;

class PerformanceBonusController extends Controller
{
    public function index()
    {
        $performanceBonuses = DB::table('performance_bonus')
            ->join('staff', 'performance_bonus.id_staff', '=', 'staff.id')
            ->select('performance_bonus.*', 'staff.first_name', 'staff.last_name')
            ->get();

        return view('performance_bonuses.index', compact('performanceBonuses'));
    }

    public function create()
    {
        $staffs = Staff::all();
        return view('performance_bonuses.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_staff' => 'required|exists:staff,id',
            'date_bonus' => 'required|date',
            'performance' => 'required|numeric|min:0',
        ]);

        try {
            PerformanceBonus::create($request->all());
            return redirect()->route('performance_bonuses.index')->with('success', 'Performance bonus added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('performance_bonuses.create')->with('error', 'Failed to add performance bonus: ' . $e->getMessage());
        }
    }

    public function edit(PerformanceBonus $performanceBonus)
    {
        $staffs = Staff::all();
        return view('performance_bonuses.edit', compact('performanceBonus', 'staffs'));
    }

    public function update(Request $request, PerformanceBonus $performanceBonus)
    {
        $request->validate([
            'id_staff' => 'required|exists:staff,id',
            'date_bonus' => 'required|date',
            'performance' => 'required|numeric|min:0',
        ]);

        try {
            $performanceBonus->update($request->all());
            return redirect()->route('performance_bonuses.index')->with('success', 'Performance bonus updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('performance_bonuses.edit', $performanceBonus->id)->with('error', 'Failed to update performance bonus: ' . $e->getMessage());
        }
    }

    public function destroy(PerformanceBonus $performanceBonus)
    {
        try {
            $performanceBonus->delete();
            return redirect()->route('performance_bonuses.index')->with('success', 'Performance bonus deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('performance_bonuses.index')->with('error', 'Failed to delete performance bonus: ' . $e->getMessage());
        }
    }
}