<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CashReconciliation;
use App\Models\Invoice;
use Carbon\Carbon;

class ReconciliationController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $reconciliation = CashReconciliation::where('date', $today)->first();
        
        $totalSales = Invoice::whereDate('created_at', $today)
            ->where('payment_method', 'cash')
            ->sum('payable_amount');

        return view('reconciliation.index', compact('reconciliation', 'totalSales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'opening_balance' => 'nullable|numeric',
            'actual_cash' => 'nullable|numeric',
        ]);

        $today = Carbon::today();
        $reconciliation = CashReconciliation::updateOrCreate(
            ['date' => $today, 'user_id' => auth()->id() ?? 1],
            [
                'opening_balance' => $request->opening_balance ?? 0,
                'actual_cash' => $request->actual_cash ?? 0,
                'expected_cash' => $request->expected_cash ?? 0,
                'difference' => ($request->actual_cash ?? 0) - ($request->expected_cash ?? 0),
                'status' => (($request->actual_cash ?? 0) == ($request->expected_cash ?? 0)) ? 'matched' : 'discrepancy',
            ]
        );

        return redirect()->back()->with('success', 'Reconciliation updated successfully');
    }
}
