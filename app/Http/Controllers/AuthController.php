<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){

        return view('auth.login');
    }


    public function post(Request $request){
        $credentials = [

            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check the user type and redirect accordingly
            if ($user->type === 'admin') {
                return redirect()->route('admin.pages');
            }
            elseif ($user->type === 'doctor') {
                return redirect()->route('doctor.pages');
            } else {

                return redirect()->route('patient.home');
            }
        }


        return back()->with('error', 'Incorrect User User Name or Password');
    }


    public function register(){

        return view('auth.register');
    }


    public function postRegister(Request $request){
        $check_email = User::where('email', $request->email)->first();
        if($check_email){
            return back()->with('error', 'Email already in use');
        }

        $user = User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact_number' => $request->contact_number
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => $request->password,
        ];

        Auth::attempt($credentials);

        return redirect()->route('patient.home')->with('success', 'Congratulations, your account can be used! After exiting, Login using User ID and Password');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
