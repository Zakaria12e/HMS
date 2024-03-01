<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkingHours;

class WorkingHoursController extends Controller
{
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'day' => 'required|string',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'doctor_id' => 'required|exists:doctors,doctor_id',
        ]);


        $workingHours = WorkingHours::create($validatedData);


        return response()->json($workingHours, 201);
    }

    public function check(Request $request)
    {
        try {
            $doctorId = $request->input('doctor_id');
            $day = $request->input('day');


            $existingWorkingHours = WorkingHours::where('doctor_id', $doctorId)->where('day', $day)->exists();

            return response()->json(['exists' => $existingWorkingHours]);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    public function read($doctorId)
{
    try {
        $workingHours = WorkingHours::where('doctor_id', $doctorId)->get();

        return response()->json($workingHours);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}


public function destroy($id)
{
    try {
        $workingHour = WorkingHours::findOrFail($id);
        $workingHour->delete();

        return response()->json(['message' => 'Working hour deleted successfully']);
    } catch (\Exception $e) {

        return response()->json(['error' => 'Failed to delete working hour'], 500);
    }
}
}
