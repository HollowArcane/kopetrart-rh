<?php

namespace App\Http\Controllers\Overtime;

use Illuminate\Http\Request;
use App\Models\Staff\Staff;
use App\Models\Overtime\StaffOvertime;
use App\Models\Overtime\OvertimeType;
use App\Models\Overtime\OvertimeShift;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StaffOvertimeController extends Controller
{
    public function index()
    {
        $monthlyOvertimes = DB::table('v_monthly_overtime')->get();
        return view('staff_overtimes.index', compact('monthlyOvertimes'));
    }

    public function create()
    {
        $staffs = Staff::all();
        $overtimeTypes = OvertimeType::all();
        $overtimeShifts = OvertimeShift::all();
        return view('staff_overtimes.create', compact('staffs', 'overtimeTypes', 'overtimeShifts'));
    }

    public function edit(StaffOvertime $staffOvertime)
    {
        $staffs = Staff::all();
        $overtimeTypes = OvertimeType::all();
        $overtimeShifts = OvertimeShift::all();
        return view('staff_overtimes.edit', compact('staffOvertime', 'staffs', 'overtimeTypes', 'overtimeShifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_staff' => 'required|exists:staff,id',
            'id_overtime_type' => 'required|exists:overtime_type,id',
            'id_overtime_shift' => 'required|exists:overtime_shift,id',
            'date_overtime' => 'required|date',
            'quantity_overtime' => 'required|numeric|min:0.1',
        ]);

        // Get the week start date for the current overtime entry
        $weekStart = \Carbon\Carbon::parse($request->date_overtime)->startOfWeek()->toDateString();

        // Calculate the total overtime for the staff member during this week
        $totalOvertimeForWeek = DB::table('v_weekly_overtime')
            ->where('id_staff', $request->id_staff)
            ->where('week_start', $weekStart)
            ->sum('total_overtime');

        // Check if the total overtime exceeds 20 hours for the week
        if ($totalOvertimeForWeek + $request->quantity_overtime > 20) {
            return redirect()->back()->withErrors(['quantity_overtime' => 'The total overtime for the week exceeds the 20-hour limit.']);
        }

        // Proceed to create the overtime record
        StaffOvertime::create($request->all());
        return redirect()->route('staff_overtimes.index')->with('success', 'Overtime record created successfully.');
    }

    public function update(Request $request, StaffOvertime $staffOvertime)
    {
        $request->validate([
            'id_staff' => 'required|exists:staff,id',
            'id_overtime_type' => 'required|exists:overtime_type,id',
            'id_overtime_shift' => 'required|exists:overtime_shift,id',
            'date_overtime' => 'required|date',
            'quantity_overtime' => 'required|numeric|min:0.1',
        ]);

        // Get the week start date for the current overtime entry
        $weekStart = \Carbon\Carbon::parse($request->date_overtime)->startOfWeek()->toDateString();

        // Calculate the total overtime for the staff member during this week, excluding the current overtime record
        $totalOvertimeForWeek = DB::table('v_weekly_overtime')
            ->where('id_staff', $request->id_staff)
            ->where('week_start', $weekStart)
            ->where('id_staff_overtime', '!=', $staffOvertime->id) // Exclude the current record
            ->sum('total_overtime');

        // Check if the total overtime exceeds 20 hours for the week
        if ($totalOvertimeForWeek + $request->quantity_overtime > 20) {
            return redirect()->back()->withErrors(['quantity_overtime' => 'The total overtime for the week exceeds the 20-hour limit.']);
        }

        // Proceed to update the overtime record
        $staffOvertime->update($request->all());
        return redirect()->route('staff_overtimes.index')->with('success', 'Overtime record updated successfully.');
    }

    public function destroy(StaffOvertime $staffOvertime)
    {
        $staffOvertime->delete();
        return redirect()->route('staff_overtimes.index')->with('success', 'Overtime record deleted successfully.');
    }
}