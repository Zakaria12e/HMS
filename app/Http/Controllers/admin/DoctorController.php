<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Department;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user' => function ($query) {
                $query->select('id', 'name', 'email', 'contact_number', 'type');
            }])
            ->withCount('appointments')
            ->paginate(5);

        return response()->json($doctors);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'specialization' => 'required|string',
            'contact_number' => 'required|string',
            'description' => 'required|string',
            'salary' => 'required|numeric',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'contact_number' => $validatedData['contact_number'],
            'password' => bcrypt($validatedData['password']),
            'department_id' => $validatedData['department_id'],
            'type' => 'doctor',
        ]);

        $doctor = Doctor::create([
            'doctor_id' => $user->id,
            'department_id' => $validatedData['department_id'],
            'specialization' => $validatedData['specialization'],
            'salary' => $validatedData['salary'],
            'description' => $validatedData['description'],
        ]);

        return response()->json($doctor, 201);
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$id,
            'specialization' => 'required|string',
            'contact_number' => 'required|string',
            'salary' => 'required|numeric',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        $doctor = Doctor::where('doctor_id', $id)->firstOrFail();

        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'contact_number' => $validatedData['contact_number'],
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->input('password'))]);
        }

        $doctor->update([
            'specialization' => $validatedData['specialization'],
            'salary' => $validatedData['salary'],
            'department_id' => $validatedData['department_id'],
            'description' => $validatedData['description'],
        ]);

        return response()->json(['message' => 'Doctor updated successfully']);
    }



    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            $departmentChef = Department::where('chef_id', $user->id)->first();

            if ($departmentChef) {
                $departmentChef->chef_id = null;
                $departmentChef->save();
            }

            User::destroy($id);
            Doctor::where('doctor_id', $id)->delete();

            return response()->json(['message' => 'Doctor deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete doctor.'], 500);
        }
    }


    public function search(Request $request)
{
    $searchQuery = $request->input('query');

    $doctors = Doctor::with(['user' => function ($query) use ($searchQuery) {
            $query->select('id', 'name', 'email', 'contact_number', 'type');
        }])
        ->whereHas('user', function ($userQuery) use ($searchQuery) {
            $userQuery->where('name', 'like', '%' . $searchQuery . '%')
                ->orWhere('email', 'like', '%' . $searchQuery . '%')
                ->orWhere('contact_number', 'like', '%' . $searchQuery . '%');
        })
        ->orWhere('specialization', 'like', '%' . $searchQuery . '%')
        ->orWhere('salary', 'like', '%' . $searchQuery . '%')
        ->withCount('appointments')
        ->paginate(5);

    return response()->json($doctors);
}

}
