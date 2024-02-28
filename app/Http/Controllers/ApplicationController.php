<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function admin()
    {

            return view("admin.layouts.app");

    }

    public function doctor()
    {

            return view("doctor.layouts.app");
    }

    public function home()
    {
        return view('patient.layouts.app');
    }
}
