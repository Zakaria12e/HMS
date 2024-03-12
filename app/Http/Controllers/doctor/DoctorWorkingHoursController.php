<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkingHours;


class DoctorWorkingHoursController extends Controller
{

    public function index($doctorId)
    {
        try {
            $workingHours = WorkingHours::where('doctor_id', $doctorId)->get();

            return response()->json($workingHours);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


}
