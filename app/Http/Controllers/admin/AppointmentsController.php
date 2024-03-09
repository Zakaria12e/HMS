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
        return Appointment::with(['patient', 'doctor'])
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


    public function create(Request $request)
   {
    $request->validate([
        'date' => 'required',
        'dueDate' => 'required',
        'description' => 'required',
        'totalAmount' => 'required',
        'appointmentId' => 'required',
    ]);

    $invoice = Invoice::create([
        'Date' => $request->input('date'),
        'DueDate' => $request->input('dueDate'),
        'DescriptionOfServices' => $request->input('description'),
        'TotalAmount' => $request->input('totalAmount'),
        'appointment_id' => $request->input('appointmentId'),
    ]);

    $appointment = Appointment::with(['patient', 'doctor'])->find($request->input('appointmentId'));

    $this->sendInvoiceSMS($appointment->patient->contact_number, $appointment, $request->input('totalAmount') , $request->input('dueDate'));


    return response()->json(['message' => 'Invoice created successfully', 'invoice' => $invoice], 200);
}

private function sendInvoiceSMS($receiverPhoneNumber, $appointment, $totalAmount , $dueDate)
{
    try {
        Log::info("Sending SMS to: " . $receiverPhoneNumber);

        $basic = new \Vonage\Client\Credentials\Basic("ca3a794c", "nbLlBFHE5DA2OBss");
        $client = new \Vonage\Client($basic);

        $messageContent = "Dear {$appointment->patient->name},\n\n"
        . "We hope this message finds you well. You have a new invoice for the appointment on {$appointment->appointment_date}.\n\n"
        . "Appointment Details:\n"
        . "- Description: {$appointment->description}\n"
        . "- Due Date: {$dueDate}\n"
        . "- Total Amount: {$totalAmount} DH\n\n"
        . "Thank you for choosing us. If you have any questions or concerns, feel free to reach out.\n\n"
        . "Best regards,\n"
        . "[HMS]";


        Log::info("SMS Content: " . $messageContent);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS(
                $receiverPhoneNumber,
                "HMS",
                $messageContent
            )
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            // Log or handle success
            Log::info("SMS sent successfully");
        } else {
            // Log or handle failure
            Log::error("SMS failed with status: " . $message->getStatus());
        }
    } catch (\Exception $e) {
        // Handle the error (log, display message, etc.)
        Log::error('Error sending SMS: ' . $e->getMessage());
    }
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
