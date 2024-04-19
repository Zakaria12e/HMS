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
        $patients = User::withCount('appointments')
            ->where('type', 'patient')
            ->get(['id', 'name', 'email', 'contact_number']);

        return response()->json($patients);
    }




    public function GetName($id)
    {
        $user = User::find($id);
        if (!$user) {return response()->json(['error' => 'User not found'], 404);}

        return response()->json($user);
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('query');

        $patient = User::where('type', 'patient')
            ->where(function ($query) use ($searchQuery) {
                $query->where('name', 'like', '%' . $searchQuery . '%')
                    ->orWhere('email', 'like', '%' . $searchQuery . '%')
                    ->orWhere('contact_number', 'like', '%' . $searchQuery . '%');
            })
            ->withCount('appointments')
            ->get();

        return response()->json($patient);
    }

}
