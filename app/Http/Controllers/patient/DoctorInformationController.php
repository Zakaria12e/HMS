<?php

namespace App\Http\Controllers\Patient;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorInformationController extends Controller
{

    public function index()
    {
        $doctors = DB::table('doctors')
            ->join('users', 'doctors.doctor_id', '=', 'users.id')
            ->leftJoin('appointments', 'doctors.doctor_id', '=', 'appointments.doctor_id')
            ->select('doctors.doctor_id','doctors.department_id', 'users.name', 'users.email','users.password', 'doctors.specialization', 'users.contact_number', 'doctors.salary', DB::raw('COUNT(appointments.doctor_id) as appointment_count'))
            ->where('users.type', 'doctor')
            ->groupBy('doctors.doctor_id','doctors.department_id', 'users.name', 'users.email', 'users.password', 'doctors.specialization', 'users.contact_number', 'doctors.salary')
            ->paginate(5);

        return response()->json($doctors);
    }



    public function getdoctor($doctorId)
    {
        $doctor = DB::table('doctors')
            ->join('users', 'doctors.doctor_id', '=', 'users.id')
            ->select('doctors.department_id', 'users.name', 'users.email', 'doctors.specialization', 'users.contact_number')
            ->where('doctors.doctor_id', $doctorId)
            ->where('users.type', 'doctor')
            ->groupBy('doctors.department_id', 'users.name', 'users.email', 'doctors.specialization', 'users.contact_number')
            ->first();

        return response()->json($doctor);
    }
}
