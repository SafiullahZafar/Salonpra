<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Today's stats
        $today = Carbon::today();
        $totalSalesToday = Invoice::whereDate('created_at', $today)->sum('payable_amount');
        $totalAppointmentsToday = Appointment::where('appointment_date', $today)->count();
        $completedAppointmentsToday = Appointment::where('appointment_date', $today)->where('status', 'completed')->count();

        // This week's stats
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $totalSalesWeek = Invoice::whereBetween('created_at', [$weekStart, $weekEnd])->sum('payable_amount');
        $totalAppointmentsWeek = Appointment::whereBetween('appointment_date', [$weekStart, $weekEnd])->count();

        // Overall stats
        $totalRevenue = Invoice::sum('payable_amount');
        $totalAppointments = Appointment::count();
        $totalCustomers = Customer::count();
        $totalUsers = User::count();
        $lowStockProducts = Product::where('track_inventory', true)->where('current_stock', '<=', 5)->count();

        // Recent appointments
        $recentAppointments = Appointment::with(['user', 'service'])->latest()->take(5)->get();

        // Recent invoices
        $recentInvoices = Invoice::with('user')->latest()->take(5)->get();

        return view('admin.index', compact(
            'totalSalesToday',
            'totalAppointmentsToday',
            'completedAppointmentsToday',
            'totalSalesWeek',
            'totalAppointmentsWeek',
            'totalRevenue',
            'totalAppointments',
            'totalCustomers',
            'totalUsers',
            'lowStockProducts',
            'recentAppointments',
            'recentInvoices'
        ));
    }
}
