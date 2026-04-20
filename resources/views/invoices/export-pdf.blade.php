<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; }
        .summary { margin-top: 30px; float: right; width: 250px; }
        .summary table td { border: none; padding: 4px; }
        .summary .total { font-weight: bold; font-size: 16px; border-top: 2px solid #333; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Report</h1>
        <p>The Crimpers - Financial Statement</p>
        <p>Generated on: {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Staff</th>
                <th>Method</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_no }}</td>
                <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
                <td>{{ $invoice->customer->name ?? $invoice->customer_name ?? 'Walk-in Customer' }}</td>
                <td>{{ $invoice->user->name ?? 'System' }}</td>
                <td>{{ strtoupper($invoice->payment_method) }}</td>
                <td>PKR {{ number_format($invoice->payable_amount, 2) }}</td>
            </tr>
            @php $total += $invoice->payable_amount; @endphp
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td>Total Transactions:</td>
                <td>{{ count($invoices) }}</td>
            </tr>
            <tr class="total">
                <td>TOTAL REVENUE:</td>
                <td>PKR {{ number_format($total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} The Crimpers Salon Management System. All rights reserved.</p>
    </div>
</body>
</html>
