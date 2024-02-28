<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PatientController extends Controller
{

    public function index()
    {
        $users = DB::table('users')
            ->leftJoin('appointments', 'users.id', '=', 'appointments.patient_id')
            ->select( 'users.name', 'users.email','users.password', DB::raw('COUNT(appointments.patient_id) as appointment_count'))
            ->where('users.type', 'patient')
            ->groupBy('users.name', 'users.email', 'users.password')
            ->get();

        return response()->json($users);
    }

    public function batchFetch(Request $request)
    {
        $userIds = $request->input('userIds', []);

        $users = User::whereIn('id', $userIds)->get();

        $userNames = $users->pluck('name', 'id');

        return response()->json($userNames);
    }


}
