<?php
namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Department;

class DoctorController extends Controller
{
    public function index()
{
    $doctors = DB::table('doctors')
        ->join('users', 'doctors.doctor_id', '=', 'users.id')
        ->leftJoin('appointments', 'doctors.doctor_id', '=', 'appointments.doctor_id')
        ->select('doctors.doctor_id','doctors.department_id','doctors.description', 'users.name', 'users.email','users.password', 'doctors.specialization', 'users.contact_number', 'doctors.salary', DB::raw('COUNT(appointments.doctor_id) as appointment_count'))
        ->where('users.type', 'doctor')
        ->groupBy('doctors.doctor_id','doctors.department_id','doctors.description' , 'users.name', 'users.email', 'users.password', 'doctors.specialization', 'users.contact_number', 'doctors.salary')
        ->paginate(5);

    return response()->json($doctors);
}


public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|string',
        'specialization' => 'required|string',
        'contact_number' => 'required|string',
        'description' => 'required|string',
        'salary' => 'required|numeric',
        'department_id' => 'nullable|exists:departments,id',
    ]);

    $existingDoctor = User::where('email', $request->email)->first();

    if ($existingDoctor) {
        return response()->json(['error' => 'Email already exists']);
    }

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
        'email' => 'required|email',
        'specialization' => 'required|string',
        'contact_number' => 'required|string',
        'salary' => 'required|numeric',
        'department_id' => 'nullable|exists:departments,id',
        'description' => 'required|string',

    ]);

    $existingDoctor = User::where('email', $validatedData['email'])
        ->where('id', '!=', $id)
        ->first();

    if ($existingDoctor) {
        return response()->json(['error' => 'Email already exists'], 422);
    }


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

        try{


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
        $query = $request->input('query');

            $doctors = DB::table('doctors')
                ->join('users', 'doctors.doctor_id', '=', 'users.id')
                ->select('doctors.doctor_id', 'doctors.department_id', 'users.name', 'users.email','users.contact_number', 'doctors.specialization', 'doctors.salary',DB::raw('COUNT(appointments.doctor_id) as appointment_count'),
                'departments.name as department_name')
                ->leftJoin('appointments', 'doctors.doctor_id', '=', 'appointments.doctor_id')
                ->leftJoin('departments', 'doctors.department_id', '=', 'departments.id')
                ->where('users.type', 'doctor')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('users.name', 'LIKE', '%' . $query . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $query . '%')
                        ->orWhere('doctors.specialization', 'LIKE', '%' . $query . '%')
                        ->orWhere('users.contact_number', 'LIKE', '%' . $query . '%')
                        ->orWhere('doctors.salary', 'LIKE', '%' . $query . '%');
                })
                ->groupBy(
            'doctors.doctor_id',
            'doctors.department_id',
            'users.name',
            'users.email',
            'users.password',
            'doctors.specialization',
            'users.contact_number',
            'doctors.salary',
            'departments.name'
        )
                ->paginate(5);


        return response()->json($doctors);
    }


}
