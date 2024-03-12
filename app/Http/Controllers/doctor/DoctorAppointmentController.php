<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

class DoctorAppointmentController extends Controller
{
    public function countAppointments(Request $request)
    {
        $doctorId = $request->input('doctor_id');
        // Your logic to count appointments for the specified doctor
        $count = Appointment::where('doctor_id', $doctorId)->count();

        return response()->json(['count' => $count]);
    }

    public function getPatientCount($doctorId)
    {
        $patientCount = Appointment::where('doctor_id', $doctorId)
            ->select('patient_id')
            ->distinct()
            ->count();

        return response()->json(['count' => $patientCount]);
    }
}
