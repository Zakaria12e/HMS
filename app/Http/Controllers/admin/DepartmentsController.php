<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentsController extends Controller
{

    public function index()
    {
        $departments = Department::with('doctors')->get();
        return response()->json($departments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'chef_id' => 'required|exists:doctors,id',
        ]);

        $department = Department::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'chef_id' => $request->input('chef_id'),
        ]);

        return response()->json($department, 201);
    }



    public function createDepartment(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'chef_id' => 'nullable|exists:users,id',
        ]);

        $newDepartment = Department::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'chef_id' => $request->input('chef_id'),
        ]);

        return response()->json($newDepartment);
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
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

        $department->delete();

        return response()->json(['message' => 'Department deleted successfully'], 200);
    } catch (\Exception $e) {

        return response()->json(['error' => 'Failed to delete department.'], 500);
    }
}

}
