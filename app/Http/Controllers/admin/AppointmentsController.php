<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppointmentsController extends Controller
{

    public function index()
    {
        return Appointment::with(['patient:id,name', 'doctor:id,name'])
            ->select('id','patient_id','status', 'doctor_id', 'title', 'service', 'description', 'appointment_date')
            ->paginate();
    }






    public function update(Request $request, $id)
    {
        try {


            $validatedData = $request->validate([
                'doctor_id' => 'required|numeric',
            ]);

            $appointment = Appointment::findOrFail($id);

            $appointment->update([
                'doctor_id' => $validatedData['doctor_id'],
            ]);

            return response()->json(['message' => 'Appointment updated successfully'], 200);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Error updating appointment', 'message' => $e->getMessage()], 500);
        }
    }



    public function destroy($id)
    {

        Appointment::where('id', $id)->delete();

        return response()->json(['message' => 'Appointment deleted successfully']);
    }






    public function invoices()
    {
        $invoices = Invoice::with('appointment.doctor', 'appointment.patient')->get();
        return response()->json($invoices);
    }

    public function markAsPaid($id)
    {

        $invoice = Invoice::findOrFail($id);

         $invoice->update(['status' => 'Payé']);

            return response()->json(['message' => 'Invoice marked as paid successfully', 'invoice' => $invoice]);

    }


}
