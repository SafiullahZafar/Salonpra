<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Appointment;
use App\Models\Invoice;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(15);

        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'birthday' => 'nullable|date',
            'preferences' => 'nullable|string',
            'membership_type' => 'nullable|string|max:100',
            'membership_expires' => 'nullable|date',
            'prepaid_credit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['prepaid_credit'] = $validated['prepaid_credit'] ?? 0;

        $customer = Customer::create($validated);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Customer profile created successfully.');
    }

    public function show(Customer $customer)
    {
        $appointments = $customer->appointments()->with(['service', 'user'])->latest()->take(8)->get();
        $invoices = $customer->invoices()->latest()->take(8)->get();

        return view('customers.show', compact('customer', 'appointments', 'invoices'));
    }

    public function sendBirthdayMessage(Customer $customer)
    {
        if (!$customer->phone || !$customer->birthday) {
            return redirect()->back()->with('error', 'Birthdate and phone number are required to send a birthday message.');
        }

        $message = "Hi {$customer->name}! 🎉\n\n" .
                   "Happy Birthday from SalonMaster! We hope your day is full of beauty and joy.\n\n" .
                   "Enjoy 20% off your next service this month as our birthday gift to you.\n\n" .
                   "Book your next appointment today! ✨\n\n" .
                   "SalonMaster - Your Beauty Destination";

        $phone = $this->formatPhoneNumber($customer->phone);

        return redirect()->away("https://wa.me/{$phone}?text=" . urlencode($message));
    }

    private function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (strlen($phone) == 10) {
            $phone = '1' . $phone;
        }

        return $phone;
    }
}
