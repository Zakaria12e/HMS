<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentsController extends Controller
{

    public function index()
    {
        $departments = Department::with(['doctors', 'doctors.user'])->get();


        return response()->json($departments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string|max:1000',
            'chef_id' => 'required|exists:doctors,doctor_id',
        ]);

        $department = Department::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'chef_id' => $request->input('chef_id'),
        ]);

        return response()->json($department, 201);
    }


    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
        'chef_id' => 'nullable|exists:doctors,doctor_id',
    ]);

    try {
        $department = Department::findOrFail($id);


        $department->name = $request->input('name');
        $department->description = $request->input('description');
        $department->chef_id = $request->input('chef_id');

        $department->save();

        return response()->json($department, 200);
    } catch (\Exception $e) {

        return response()->json(['error' => 'Failed to update department.'], 500);
    }
}

public function destroy($id)
{
    try {
        $department = Department::findOrFail($id);

        $department->doctors()->update(['department_id' => null]);

        $department->delete();

        return response()->json(['message' => 'Department deleted successfully'], 200);
    } catch (\Exception $e) {

        return response()->json(['error' => 'Failed to delete department.'], 500);
    }
}

}
