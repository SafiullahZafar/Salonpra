<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['user', 'service'])->latest()->paginate(20);
        return view('appointments.index', compact('appointments'));
    }

    public function calendar()
    {
        $users = User::all();
        $services = Service::all();
        return view('appointments.calendar', compact('users', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'customer_name' => 'required|string',
            'customer_phone' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Check for conflicts
        $conflict = Appointment::where('user_id', $request->user_id)
            ->where('appointment_date', $request->appointment_date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'This time slot is already booked.']);
        }

        Appointment::create($request->all());

        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully');
    }

    public function edit(Appointment $appointment)
    {
        $users = User::all();
        $services = Service::all();
        return view('appointments.edit', compact('appointment', 'users', 'services'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'customer_name' => 'required|string',
            'customer_phone' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Check for conflicts excluding current appointment
        $conflict = Appointment::where('user_id', $request->user_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('id', '!=', $appointment->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'This time slot is already booked.']);
        }

        $appointment->update($request->all());

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully');
    }

    public function events()
    {
        $appointments = Appointment::with(['user', 'service'])->get();
        $events = [];

        foreach ($appointments as $appointment) {
            $events[] = [
                'id' => $appointment->id,
                'title' => $appointment->customer_name . ' - ' . $appointment->service->name,
                'start' => $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->start_time->format('H:i:s'),
                'end' => $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->end_time->format('H:i:s'),
                'backgroundColor' => $appointment->status == 'completed' ? '#22c55e' : ($appointment->status == 'cancelled' ? '#dc2626' : '#3b82f6'),
                'extendedProps' => [
                    'staff' => $appointment->user->name,
                    'service' => $appointment->service->name,
                    'customer' => $appointment->customer_name,
                ]
            ];
        }

        return response()->json($events);
    }

    public function updateTime(Request $request, Appointment $appointment)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        // Check for conflicts
        $conflict = Appointment::where('user_id', $appointment->user_id)
            ->where('appointment_date', $start->format('Y-m-d'))
            ->where('id', '!=', $appointment->id)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start->format('H:i'), $end->format('H:i')])
                      ->orWhereBetween('end_time', [$start->format('H:i'), $end->format('H:i')])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('start_time', '<=', $start->format('H:i'))
                            ->where('end_time', '>=', $end->format('H:i'));
                      });
            })
            ->exists();

        if ($conflict) {
            return response()->json(['error' => 'Time slot conflict'], 422);
        }

        $appointment->update([
            'appointment_date' => $start->format('Y-m-d'),
            'start_time' => $start->format('H:i'),
            'end_time' => $end->format('H:i'),
        ]);

        return response()->json(['success' => true]);
    }

}