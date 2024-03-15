<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalReport;


class MedicalReportController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'diagnosis' => 'required|string',
            'medications' => 'required|string',
            'recommendations' => 'required|string',
            'symptoms' => 'required|string',
            'appointment_id' => 'required|exists:appointments,id'
        ]);

        // Create a new medical report
        $medicalReport = MedicalReport::create([
            'title' => $validatedData['title'],
            'diagnosis' => $validatedData['diagnosis'],
            'medications' => $validatedData['medications'],
            'recommendations' => $validatedData['recommendations'],
            'symptoms' => $validatedData['symptoms'],
            'appointment_id' => $validatedData['appointment_id']
        ]);

        // Optionally, you can return the created medical report as a response
        return response()->json(['message' => 'Medical report created successfully', 'medical_report' => $medicalReport], 200);
    }
}
