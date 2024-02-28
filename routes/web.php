<?php

use App\Http\Controllers\admin\DoctorController;
use App\Http\Controllers\admin\PatientController;
use App\Http\Controllers\admin\AppointmentsController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\DepartmentsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'post'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'postRegister'])->name('register.store');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {







 Route::middleware(['auth', 'userType:doctor'])->group(function () {
//doctor  routes
    Route::get('/doctor/{view?}', [ApplicationController::class, 'doctor'])->where('view', '.*')->name('doctor.pages');

 });

 Route::middleware(['auth', 'userType:admin'])->group(function () {

//admin  routes
Route::get('/admin/{view?}', [ApplicationController::class, 'admin'])->where('view', '.*')->name('admin.pages');
Route::get('/api/doctors', [DoctorController::class, 'index']);
Route::post('/api/doctors', [DoctorController::class, 'store']);
Route::get('/api/doctors/search', [DoctorController::class, 'search']);
Route::put('/api/doctors/{id}', [DoctorController::class, 'update']);
Route::delete('/api/doctors/{id}', [DoctorController::class, 'destroy']);

Route::post('/api/users/batch', [PatientController::class, 'batchFetch']);
Route::get('/api/users', [PatientController::class, 'index']);

Route::get('/api/appointments', [AppointmentsController::class, 'index']);
Route::put('/api/appointments/{id}', [AppointmentsController::class, 'update']);
Route::delete('/api/appointments/{id}', [AppointmentsController::class, 'destroy']);
Route::post('/api/invoices', [AppointmentsController::class, 'create']);
Route::get('/api/invoices', [AppointmentsController::class, 'invoices']);
Route::put('/api/invoices/{id}', [AppointmentsController::class, 'markAsPaid']);

Route::get('/api/dashboard/users-count', [DashboardController::class, 'getUsersCount']);
Route::get('/api/dashboard/appointment-count', [DashboardController::class, 'getAppointmentCount']);
Route::get('/api/dashboard/doctors-count', [DashboardController::class, 'getDoctorsCount']);
Route::get('/api/dashboard/invoices-count', [DashboardController::class, 'getInvoicesCount']);
Route::get('/api/dashboard/total-paid-amount', [DashboardController::class, 'totalPaidAmount']);



Route::get('/api/departments', [DepartmentsController::class, 'index']);
Route::post('/api/departments', [DepartmentsController::class, 'store']);

 });


    //patient routes
    Route::get('/home', [ApplicationController::class, 'home'])->name('patient.home');



});



















