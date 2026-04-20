<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;

class WhatsAppController extends Controller
{
    /**
     * Send appointment reminder
     */
    public function sendAppointmentReminder(Appointment $appointment)
    {
        $message = "Hi {$appointment->customer_name}! 👋\n\n" .
                  "This is a reminder for your appointment at SalonMaster.\n\n" .
                  "📅 Date: {$appointment->appointment_date->format('l, F j, Y')}\n" .
                  "⏰ Time: {$appointment->start_time->format('g:i A')} - {$appointment->end_time->format('g:i A')}\n" .
                  "💇 Service: {$appointment->service->name}\n" .
                  "👨‍💼 Staff: {$appointment->user->name}\n\n" .
                  "We look forward to seeing you! ✨\n\n" .
                  "SalonMaster - Your Beauty Destination";

        $phone = $this->formatPhoneNumber($appointment->customer_phone);

        return redirect()->away("https://wa.me/{$phone}?text=" . urlencode($message));
    }

    /**
     * Send booking confirmation
     */
    public function sendBookingConfirmation(Appointment $appointment)
    {
        $message = "Hi {$appointment->customer_name}! 🎉\n\n" .
                  "Your appointment has been confirmed at SalonMaster!\n\n" .
                  "📅 Date: {$appointment->appointment_date->format('l, F j, Y')}\n" .
                  "⏰ Time: {$appointment->start_time->format('g:i A')} - {$appointment->end_time->format('g:i A')}\n" .
                  "💇 Service: {$appointment->service->name}\n" .
                  "👨‍💼 Staff: {$appointment->user->name}\n\n" .
                  "📍 Address: [Your Salon Address]\n" .
                  "📞 Contact: [Your Phone Number]\n\n" .
                  "See you soon! 💅✨\n\n" .
                  "SalonMaster";

        $phone = $this->formatPhoneNumber($appointment->customer_phone);

        return redirect()->away("https://wa.me/{$phone}?text=" . urlencode($message));
    }

    /**
     * Send promotional message
     */
    public function sendPromotion(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $message = $request->message . "\n\nSalonMaster - Your Beauty Destination ✨";

        $phone = $this->formatPhoneNumber($request->phone);

        return redirect()->away("https://wa.me/{$phone}?text=" . urlencode($message));
    }

    /**
     * Send re-engagement message to inactive customers
     */
    public function sendReengagement(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'customer_name' => 'required|string',
        ]);

        $message = "Hi {$request->customer_name}! 👋\n\n" .
                  "We haven't seen you at SalonMaster in a while! 💇‍♀️\n\n" .
                  "🌟 Special offer: 20% off on any service this month!\n" .
                  "🎁 Book now and get complimentary consultation\n\n" .
                  "Ready to treat yourself? We're waiting for you! ✨\n\n" .
                  "Book online: [Your Website]\n" .
                  "Call us: [Your Phone]\n\n" .
                  "SalonMaster - Your Beauty Destination";

        $phone = $this->formatPhoneNumber($request->phone);

        return redirect()->away("https://wa.me/{$phone}?text=" . urlencode($message));
    }

    /**
     * Share invoice via WhatsApp
     */
    public function shareInvoice(Invoice $invoice)
    {
        $customerName = $invoice->customer ? $invoice->customer->name : ($invoice->user ? $invoice->user->name : 'Valued Customer');
        $phone = $invoice->customer ? $invoice->customer->phone : null;

        $message = "Hi {$customerName}! 💳\n\n" .
                  "Here's your invoice from SalonMaster:\n\n" .
                  "🧾 Invoice #: {$invoice->invoice_no}\n" .
                  "📅 Date: {$invoice->created_at->format('F j, Y')}\n" .
                  "💰 Total: \${$invoice->payable_amount}\n" .
                  "💳 Payment: " . ucfirst($invoice->payment_method) . "\n\n" .
                  "Thank you for choosing SalonMaster! 🌟\n" .
                  "We hope to see you again soon!\n\n" .
                  "💅✨ #SalonMaster #Beauty";

        if (!$phone) {
            return redirect()->back()->with('error', 'Cannot send WhatsApp invoice: customer phone number is missing.');
        }

        $phone = $this->formatPhoneNumber($phone);

        return redirect()->away("https://wa.me/{$phone}?text=" . urlencode($message));
    }

    /**
     * Show WhatsApp management page
     */
    public function index()
    {
        $customers = Customer::whereNotNull('phone')->orderBy('updated_at', 'desc')->limit(20)->get();
        $birthdays = Customer::whereNotNull('birthday')
            ->whereMonth('birthday', Carbon::now()->month)
            ->orderBy('birthday')
            ->get();

        return view('whatsapp.index', compact('customers', 'birthdays'));
    }

    /**
     * Format phone number for WhatsApp
     */
    private function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/\D/', '', $phone);

        // Add country code if missing (assuming US +1)
        if (strlen($phone) == 10) {
            $phone = '1' . $phone;
        }

        return $phone;
    }
}
