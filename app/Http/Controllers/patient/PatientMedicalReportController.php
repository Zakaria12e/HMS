<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalReport;
use Illuminate\Http\Request;

class PatientMedicalReportController extends Controller
{
    public function getReports(Request $request)
    {
        $userId = $request->query('userId');


        $medicalReports = MedicalReport::whereHas('appointment', function ($query) use ($userId) {
            $query->where('patient_id', $userId);
        })->with('appointment.doctor')->get();

        return response()->json($medicalReports);
    }
}
