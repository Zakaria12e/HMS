<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Invoice;

class DashboardController extends Controller
{

    public function getUsersCount()
    {

        $totalUsersCount = User::query()
        ->when(request('date_range') === 'today', function ($query) {
            $query->whereBetween('created_at', [now()->today(), now()]);
        })
        ->when(request('date_range') === '30_days', function ($query) {
            $query->whereBetween('created_at', [now()->subDays(30), now()]);
        })
        ->when(request('date_range') === '60_days', function ($query) {
            $query->whereBetween('created_at', [now()->subDays(60), now()]);
        })
        ->when(request('date_range') === '360_days', function ($query) {
            $query->whereBetween('created_at', [now()->subDays(360), now()]);
        })
        ->when(request('date_range') === 'year_to_date', function ($query) {
            $query->whereBetween('created_at', [now()->firstOfYear(), now()]);
        })
        ->count();

    return response()->json(['count' => $totalUsersCount]);

    }

    public function getAppointmentCount(): JsonResponse
    {
        $appointmentCount = Appointment::count();

        return response()->json(['count' => $appointmentCount]);
    }

    public function getDoctorsCount(): JsonResponse
    {
        $doctorsCount = Doctor::count();

        return response()->json(['count' => $doctorsCount]);
    }

    public function getInvoicesCount(): JsonResponse
    {
        $invoicesCount = Invoice::count();

        return response()->json(['count' => $invoicesCount]);
    }

    public function totalPaidAmount()
{
    $totalPaidAmount = Invoice::where('status', 'Payé')->sum('TotalAmount');

    return response()->json(['totalPaidAmount' => $totalPaidAmount]);
}


}
