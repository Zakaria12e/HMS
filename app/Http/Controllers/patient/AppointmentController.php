<?php

namespace App\Http\Controllers\patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:doctors,doctor_id',
            'title' => 'required|string',
            'service' => 'required|string',
            'date' => 'required|date',
            'time_slot' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $startTime = Carbon::parse($request->input('time_slot'));
        $endTime = $startTime->addMinutes(30);

        $appointment = Appointment::create([
            'patient_id' => $request->input('patient_id'),
            'doctor_id' => $request->input('doctor_id'),
            'title' => $request->input('title'),
            'service' => $request->input('service'),
            'description' => $request->input('description'),
            'appointment_date' => $request->input('date'),
            'start_time' => $request->input('time_slot'),
            'end_time' => $endTime->toTimeString(),
        ]);

        return response()->json(['message' => 'Appointment submitted successfully', 'data' => $appointment], 201);
    }

    public function getUserAppointments(Request $request)
    {
        $userId = $request->input('userId');


        $appointments = Appointment::with('doctor:id,name')->where('patient_id', $userId)->get();




        return response()->json($appointments);

    }
    public function checkAvailability($doctorId, Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'time' => 'required|string',
    ]);

    $date = $request->input('date');
    $time = $request->input('time');

    $existingAppointments = Appointment::where('doctor_id', $doctorId)
        ->where('appointment_date', $date)
        ->where('start_time', $time)
        ->exists();

    return response()->json(['available' => !$existingAppointments]);
}

public function cancelAppointment($id)
{
    try {

        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'Annulé';
        $appointment->save();

        return response()->json($appointment, 200);
    } catch (\Exception $e) {
      
        return response()->json(['error' => 'Internal server error'], 500);
    }
}

}
