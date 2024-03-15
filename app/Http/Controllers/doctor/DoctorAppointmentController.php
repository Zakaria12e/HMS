<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DoctorAppointmentController extends Controller
{



    public function getAppointmentsByDoctor(Request $request)
    {
        $doctorId = $request->query('doctor_id');
        $status = $request->query('status');

        if (!$doctorId) {
            return response()->json(['error' => 'doctor_id parameter is required'], 400);
        }

        $query = Appointment::where('doctor_id', $doctorId);

        if ($status) {
            $query->where('status', $status);
        }

        $appointments = $query->get();

        foreach ($appointments as $appointment) {
            $patient = User::find($appointment->patient_id);
            if ($patient) {
                $appointment->patient_name = $patient->name;
            } else {
                $appointment->patient_name = 'Unknown';
            }
        }

        return response()->json($appointments);
    }

    public function getAppointmentCounts(Request $request)
    {
        try {

            $doctorId = $request->input('doctor_id');

            if (!$doctorId) {
                return response()->json(['error' => 'doctor_id parameter is required'], 400);
            }

            $counts = Appointment::where('doctor_id', $doctorId)
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            return response()->json($counts);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Planifié,Confirmé,Fermé',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update($validated);

        return response()->json(['message' => 'Status updated successfully'], 200);
    }



    public function countAppointments(Request $request)
    {
        $doctorId = $request->input('doctor_id');

        $count = Appointment::where('doctor_id', $doctorId)->count();

        return response()->json(['count' => $count]);
    }

    public function getPatientCount($doctorId)
    {
        $patientCount = Appointment::where('doctor_id', $doctorId)
            ->distinct('patient_id')
            ->count();

        return response()->json(['count' => $patientCount]);
    }


    public function create(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'dueDate' => 'required',
            'description' => 'required',
            'totalAmount' => 'required',
            'appointmentId' => 'required',
        ]);

        $appointmentId = $request->input('appointmentId');

        $patientId = Appointment::where('id', $appointmentId)->value('patient_id');

        $receiverPhoneNumber = User::where('id', $patientId)->value('contact_number');

        $invoice = Invoice::create([
            'Date' => $request->input('date'),
            'DueDate' => $request->input('dueDate'),
            'DescriptionOfServices' => $request->input('description'),
            'TotalAmount' => $request->input('totalAmount'),
            'appointment_id' => $appointmentId,
        ]);

        $appointment = Appointment::with('patient')->find($appointmentId);

        $this->sendInvoiceSMS($receiverPhoneNumber, $appointment, $request->input('totalAmount'), $request->input('dueDate'));

        return response()->json(['message' => 'Invoice created successfully', 'invoice' => $invoice], 200);
    }

    
 private function sendInvoiceSMS($receiverPhoneNumber, $appointment, $totalAmount , $dueDate)
 {
     try {
         Log::info("Sending SMS to: " . $receiverPhoneNumber);

         $basic = new \Vonage\Client\Credentials\Basic("120111b4", "amHSfINBuVQc7LNj");
         $client = new \Vonage\Client($basic);

         $messageContent = "Dear {$appointment->patient->name},\n\n"
         . "We hope this message finds you well. You have a new invoice for the appointment on {$appointment->appointment_date}.\n\n"
         . "Appointment Details:\n"
         . "- Description: {$appointment->description}\n"
         . "- Due Date: {$dueDate}\n"
         . "- Total Amount: {$totalAmount} DH\n\n";


         Log::info("SMS Content: " . $messageContent);

         $response = $client->sms()->send(
             new \Vonage\SMS\Message\SMS(
                 $receiverPhoneNumber,
                 "HMS ZAKARIA",
                 $messageContent
             )
         );

         $message = $response->current();

         if ($message->getStatus() == 0) {

             Log::info("SMS sent successfully");
         } else {

             Log::error("SMS failed with status: " . $message->getStatus());
         }
     } catch (\Exception $e) {

         Log::error('Error sending SMS: ' . $e->getMessage());
     }
 }


 public function getinvoices($doctorId)
 {
     try {
         // Find appointments where the doctor_id matches
         $doctorAppointments = Appointment::where('doctor_id', $doctorId)->pluck('id');

         // Find invoices where the appointment_id is in the list of appointments found
         $invoices = Invoice::whereIn('appointment_id', $doctorAppointments)
             ->with('appointment.patient')
             ->get();

         return response()->json($invoices);
     } catch (\Exception $e) {
         return response()->json(['error' => $e->getMessage()], 500);
     }
 }



}
