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

}
