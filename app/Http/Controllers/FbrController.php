<?php

namespace App\Http\Controllers;

use App\Models\FbrLog;
use App\Models\Invoice;
use Illuminate\Http\Request;

class FbrController extends Controller
{
    public function index()
    {
        $logs = FbrLog::with('invoice')->latest()->paginate(20);
        $totalSimulated = FbrLog::count();
        $todayCount = FbrLog::whereDate('created_at', today())->count();
        return view('fbr.index', compact('logs', 'totalSimulated', 'todayCount'));
    }

    public function show(Invoice $invoice)
    {
        $log = FbrLog::where('invoice_id', $invoice->id)->latest()->firstOrFail();
        return view('fbr.show', compact('log', 'invoice'));
    }
}
