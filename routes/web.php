<?php

use App\Http\Controllers\admin\DoctorController;
use App\Http\Controllers\admin\PatientController;
use App\Http\Controllers\admin\AppointmentsController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\DepartmentsController;
use App\Http\Controllers\admin\WorkingHoursController;
use App\Http\Controllers\Patient\DoctorInformationController;
use App\Http\Controllers\patient\AppointmentController;
use App\Http\Controllers\doctor\DoctorAppointmentController;
use App\Http\Controllers\doctor\DoctorWorkingHoursController;
use App\Http\Controllers\doctor\MedicalReportController;
use App\Http\Controllers\patient\PatientMedicalReportController;
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

    Route::get('/api/appointments/count', [DoctorAppointmentController::class, 'countAppointments']);
    Route::get('/api/appointments/patient-count/{doctor_id}', [DoctorAppointmentController::class, 'getPatientCount'])->name('doctor.appointments.patient_count');
    Route::get('/api/doctor/working-hours/{doctor}', [DoctorWorkingHoursController::class, 'index']);
    Route::get('/api/doctor/appointments', [DoctorAppointmentController::class, 'getAppointmentsByDoctor']);
    Route::get('/api/appointment-counts', [DoctorAppointmentController::class, 'getAppointmentCounts']);
    Route::put('/api/changeStatusofAppointments/{id}', [DoctorAppointmentController::class, 'updateStatus']);
    Route::delete('/api/doctorAppointments/{id}', [DoctorAppointmentController::class, 'destroy']);
    Route::post('/api/invoices', [DoctorAppointmentController::class, 'create']);
    Route::get('/api/doctor/getusers', [DoctorAppointmentController::class, 'doctorPatients']);
    Route::get('/api/getinvoices/doctor/{doctorId}', [DoctorAppointmentController::class, 'getinvoices']);
    Route::get('/api/doctor/patients/search', [DoctorAppointmentController::class, 'search']);
    Route::post('/api/medical-reports', [MedicalReportController::class, 'store']);
    Route::get('/api/medical-reports', [MedicalReportController::class, 'getMedicalReports']);






 });

 Route::middleware(['auth', 'userType:admin'])->group(function () {

//admin  routes
Route::get('/admin/{view?}', [ApplicationController::class, 'admin'])->where('view', '.*')->name('admin.pages');
Route::get('/api/doctors', [DoctorController::class, 'index']);
Route::post('/api/doctors', [DoctorController::class, 'store']);
Route::get('/api/doctors/getDoctorsForDepartments', [DoctorController::class, 'index']);
Route::get('/api/doctors/search', [DoctorController::class, 'search']);
Route::put('/api/doctors/{id}', [DoctorController::class, 'update']);
Route::delete('/api/doctors/{id}', [DoctorController::class, 'destroy']);


Route::post('/api/working-hours', [WorkingHoursController::class, 'store']);
Route::get('/api/working-hours/check', [WorkingHoursController::class, 'check']);
Route::get('/api/working-hours-read/{doctorId}', [WorkingHoursController::class, 'read']);
Route::delete('/api/working-hours/{dayId}', [WorkingHoursController::class, 'destroy']);


Route::get('/api/users', [PatientController::class, 'index']);
Route::get('/api/users/{id}', [PatientController::class,'GetName']);


Route::get('/api/appointments', [AppointmentsController::class, 'index']);
Route::put('/api/appointments/{id}', [AppointmentsController::class, 'update']);
Route::delete('/api/appointments/{id}', [AppointmentsController::class, 'destroy']);
Route::get('/api/getinvoices', [AppointmentsController::class, 'invoices']);
Route::put('/api/invoices/{id}', [AppointmentsController::class, 'markAsPaid']);

Route::get('/api/dashboard/users-count', [DashboardController::class, 'getUsersCount']);
Route::get('/api/dashboard/appointment-count', [DashboardController::class, 'getAppointmentCount']);
Route::get('/api/dashboard/doctors-count', [DashboardController::class, 'getDoctorsCount']);
Route::get('/api/dashboard/invoices-count', [DashboardController::class, 'getInvoicesCount']);
Route::get('/api/dashboard/total-paid-amount', [DashboardController::class, 'totalPaidAmount']);



Route::post('/api/departments', [DepartmentsController::class, 'store']);
Route::put('/api/departments/{id}', [DepartmentsController::class, 'update']);
Route::delete('/api/departments/{id}', [DepartmentsController::class, 'destroy']);



 });


    //patient routes
    Route::get('/home', [ApplicationController::class, 'home'])->name('patient.home');
    Route::get('/patient/doctorinformation/{doctorId}', [DoctorInformationController::class, 'getdoctor']);
    Route::get('/api/patient/doctors', [DoctorInformationController::class, 'index']);
    Route::get('/api/working-days-hours/{doctorId}', [WorkingHoursController::class, 'read']);
    Route::get('/api/working-hours/{doctorId}', [WorkingHoursController::class, 'getWorkingHours']);
    Route::post('/api/create-appointment', [AppointmentsController::class, 'store']);
    Route::get('/api/departments', [DepartmentsController::class, 'index']);
    Route::post('/api/store_appointment', [AppointmentController::class, 'store']);
    Route::get('/api/Myappointments', [AppointmentController::class, 'getUserAppointments']);
    Route::get('/api/check-availability/{doctorId}', [AppointmentController::class, 'checkAvailability']);
    Route::put('/api/Myappointments/{id}', [AppointmentController::class, 'cancelAppointment']);
    Route::get('/api/patient/medical-reports', [PatientMedicalReportController::class, 'getReports']);




});



















