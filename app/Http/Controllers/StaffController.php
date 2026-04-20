<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\StaffShift;
use App\Models\LeaveRequest;
use App\Models\UpsellPerformance;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with(['attendances', 'shifts', 'leaveRequests', 'upsellPerformance'])->paginate(12);
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        $services = Service::all();
        return view('staff.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff',
            'phone' => 'nullable|string',
            'position' => 'required|string',
            'hourly_rate' => 'required|numeric|min:0',
            'hiring_date' => 'required|date',
            'status' => 'nullable|boolean',
            'bio' => 'nullable|string',
            'service_ids' => 'nullable|array',
        ]);

        $staff = Staff::create($validated);
        $staff->services()->sync($request->input('service_ids', []));
        UpsellPerformance::create(['staff_id' => $staff->id]);

        return redirect()->route('staff.index')->with('success', 'Staff member added.');
    }

    public function show(Staff $staff)
    {
        $attendances = $staff->attendances()->latest()->paginate(10);
        $shifts = $staff->shifts()->latest()->paginate(10);
        $leaveRequests = $staff->leaveRequests()->latest()->paginate(10);
        $upsellPerformance = $staff->upsellPerformance;

        $monthlyHours = $staff->attendances()
            ->whereYear('attendance_date', now()->year)
            ->whereMonth('attendance_date', now()->month)
            ->sum('check_out_time') - $staff->attendances()
            ->whereYear('attendance_date', now()->year)
            ->whereMonth('attendance_date', now()->month)
            ->sum('check_in_time');

        return view('staff.show', compact('staff', 'attendances', 'shifts', 'leaveRequests', 'upsellPerformance', 'monthlyHours'));
    }

    public function edit(Staff $staff)
    {
        $services = Service::all();
        return view('staff.edit', compact('staff', 'services'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
            'phone' => 'nullable|string',
            'position' => 'required|string',
            'hourly_rate' => 'required|numeric|min:0',
            'hiring_date' => 'required|date',
            'status' => 'nullable|boolean',
            'bio' => 'nullable|string',
            'service_ids' => 'nullable|array',
        ]);

        $staff->update($validated);
        $staff->services()->sync($request->input('service_ids', []));

        return redirect()->route('staff.show', $staff)->with('success', 'Staff member updated.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff member deleted.');
    }

    public function attendance(Request $request, Staff $staff = null)
    {
        if ($staff) {
            $records = $staff->attendances()->paginate(15);
            return view('staff.attendance', compact('staff', 'records'));
        }
        $records = StaffAttendance::with('staff')->paginate(20);
        return view('staff.attendance-all', compact('records'));
    }

    public function recordAttendance(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'attendance_date' => 'required|date',
            'check_in_time' => 'nullable|datetime',
            'check_out_time' => 'nullable|datetime',
            'status' => 'required|in:present,absent,late,half_day,leave',
            'notes' => 'nullable|string',
        ]);

        StaffAttendance::updateOrCreate(
            ['staff_id' => $validated['staff_id'], 'attendance_date' => $validated['attendance_date']],
            $validated
        );

        return redirect()->back()->with('success', 'Attendance recorded.');
    }

    public function shifts(Staff $staff)
    {
        $shifts = $staff->shifts()->paginate(15);
        return view('staff.shifts', compact('staff', 'shifts'));
    }

    public function createShift(Staff $staff)
    {
        return view('staff.create-shift', compact('staff'));
    }

    public function storeShift(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'shift_date' => 'required|date',
            'start_time' => 'required|datetime',
            'end_time' => 'required|datetime',
            'shift_type' => 'required|in:morning,afternoon,evening,full_day',
            'break_duration' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $staff->shifts()->create($validated);

        return redirect()->route('staff.shifts', $staff)->with('success', 'Shift scheduled.');
    }

    public function leaveRequests(Staff $staff = null)
    {
        if ($staff) {
            $requests = $staff->leaveRequests()->paginate(12);
            return view('staff.leave-requests', compact('staff', 'requests'));
        }
        $requests = LeaveRequest::with('staff')->where('status', 'pending')->paginate(15);
        return view('staff.leave-requests-all', compact('requests'));
    }

    public function requestLeave(Staff $staff)
    {
        return view('staff.request-leave', compact('staff'));
    }

    public function storeLeaveRequest(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'leave_type' => 'required|in:sick,vacation,personal,unpaid',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $staff->leaveRequests()->create([
            ...$validated,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Leave request submitted.');
    }

    public function approveLeave(LeaveRequest $leave)
    {
        $leave->update([
            'status' => 'approved',
            'approved_by' => Auth::id() ?? 1,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Leave request approved.');
    }

    public function rejectLeave(LeaveRequest $leave)
    {
        $leave->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Leave request rejected.');
    }

    public function performanceDashboard()
    {
        $staff = Staff::with('upsellPerformance')->get();
        return view('staff.performance-dashboard', compact('staff'));
    }
}
