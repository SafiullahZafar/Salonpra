<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_no }}</title>
    <style>
        @page {
            margin: 0;
            size: 80mm 200mm; /* Standard Thermal Receipt Size */
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.2;
            margin: 0;
            padding: 20px 0;
            background: #e5e7eb;
            color: #000;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .ticket-wrapper {
            width: 75mm;
            background: #fff;
            padding: 10px 10px 30px 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            margin: 0 auto;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .dashed-line { border-top: 1px dashed #000; margin: 5px 0; }
        .double-dashed { border-top: 3px double #000; margin: 5px 0; }
        
        .header { margin-bottom: 10px; }
        .brand-name { font-size: 18px; font-weight: 900; margin-bottom: 2px; text-transform: uppercase; }
        .header-info { font-size: 9px; margin-bottom: 2px; }

        .meta-row { display: flex; justify-content: space-between; margin-bottom: 2px; font-size: 10px; }
        
        .items-header { display: grid; grid-template-columns: 15px 1fr 40px 30px 25px 50px; font-size: 9px; font-weight: bold; margin-bottom: 2px; }
        .item-row { display: grid; grid-template-columns: 15px 1fr 40px 30px 25px 50px; font-size: 9px; margin-bottom: 4px; align-items: start; }
        .item-row .desc { grid-column: 1 / span 6; font-weight: bold; margin-bottom: 1px; }

        .summary-section { margin-top: 10px; }
        .summary-row { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 2px; }
        .summary-row.total { font-size: 13px; font-weight: bold; padding-top: 5px; margin-top: 5px; border-top: 1px solid #000; }

        .payment-section { margin-top: 10px; padding: 5px; border: 1px solid #000; }
        
        .fbr-section { margin-top: 20px; border-top: 2px solid #000; padding-top: 10px; }
        .fbr-id { font-size: 14px; font-weight: 900; letter-spacing: 1px; margin: 5px 0; }
        .qr-code { margin: 10px auto; display: block; }
        
        .footer { font-size: 9px; margin-top: 15px; }

        @media print {
            .no-print { display: none; }
            body { padding: 0; background: #fff; display: block; text-align: center; }
            .ticket-wrapper { box-shadow: none; margin: 0 auto; display: inline-block; text-align: left; padding-bottom: 0; }
        }
    </style>
</head>
<body>
<div class="ticket-wrapper">
    <div class="header text-center">
        <div class="brand-name">The Crimpers</div>
        <div class="header-info">hop no. 5, Sargodha Rd, inside Pearl City Plaza, Canal Block Shadman Town, Faisalabad</div>
        <div class="header-info">Phone:  0300 7614788</div>
        <div class="header-info">NTN: 4401860-3</div>
    </div>

    <div class="dashed-line"></div>

    <div class="metadata">
        <div class="meta-row">
            <span>Invoice #: <span class="font-bold">{{ $invoice->invoice_no }}</span></span>
            <span>POS No.: <span class="font-bold">{{ $invoice->fbrLog->payload['POSID'] ?? '135793' }}</span></span>
        </div>
        <div class="meta-row">
            <span>Cashier: <span class="font-bold">{{ strtoupper($invoice->user->name ?? 'ADMIN') }}</span></span>
            <span>{{ $invoice->created_at->format('d/m/Y H:i:s') }}</span>
        </div>
        <div class="meta-row">
            <span>Mode of Payment: <span class="font-bold">{{ strtoupper($invoice->payment_method) }}</span></span>
        </div>
        <div class="meta-row">
            <span>Customer: <span class="font-bold">{{ strtoupper($invoice->customer_name ?: ($invoice->customer->name ?? 'WALK-IN CUSTOMER')) }}</span></span>
        </div>
        @if($invoice->remarks)
        <div class="meta-row">
            <span>Remarks: {{ $invoice->remarks }}</span>
        </div>
        @endif
    </div>

    <div class="dashed-line"></div>

    <div class="items-header">
        <span>#</span>
        <span>Description</span>
        <span class="text-right">Price</span>
        <span class="text-right">GST</span>
        <span class="text-right">Qty</span>
        <span class="text-right">Total</span>
    </div>

    <div class="dashed-line"></div>

    @foreach($invoice->items as $index => $item)
    <div class="item-row">
        <div class="desc">{{ $item->itemizable->name ?? 'Service' }}</div>
        <span>{{ $index + 1 }}</span>
        <span></span> <!-- Placeholder for desc line 2 -->
        <span class="text-right">{{ number_format($item->price, 2) }}</span>
        <span class="text-right">{{ number_format($item->subtotal * 0.05, 2) }}</span>
        <span class="text-right font-bold">{{ $item->quantity }}</span>
        <span class="text-right">{{ number_format($item->subtotal + ($item->subtotal * 0.05), 2) }}</span>
    </div>
    @endforeach

    <div class="dashed-line"></div>

    <div class="summary-section">
        <div class="summary-row">
            <span>Total Qty: {{ $invoice->items->sum('quantity') }}</span>
            <span class="font-bold text-right">Total Amount: {{ number_format($invoice->total_amount, 2) }}</span>
        </div>
        <div class="summary-row">
            <span></span>
            <span class="text-right">Discount: {{ number_format($invoice->discount, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>Total GST (5%): {{ number_format($invoice->tax, 2) }}</span>
            <span class="text-right">POS Service Fee: 1.00</span>
        </div>
        <div class="summary-row total">
            <span>Payable:</span>
            <span class="text-right">{{ number_format($invoice->payable_amount, 2) }}</span>
        </div>
    </div>

    <div class="dashed-line"></div>

    <div class="payment-section">
        @php
            $tendered = $invoice->tendered_amount ?? $invoice->payable_amount;
            $balance = max(0, $tendered - $invoice->payable_amount);
        @endphp
        <div class="summary-row">
            <span class="font-bold">Cash Tendered:</span>
            <span class="text-right font-bold">{{ number_format($tendered, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="font-bold">Amount Charged:</span>
            <span class="text-right font-bold">{{ number_format($invoice->payable_amount, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="font-bold">Balance:</span>
            <span class="text-right font-bold">{{ number_format($balance, 2) }}</span>
        </div>
    </div>

    <div class="footer text-center">
        <p>Thanks For Visiting.</p>
        {{-- <p>No Return No Exchange For Services</p> --}}
        <p>For Complaint & Queries:  0300 7614788</p>
        <p style="font-size: 8px;">(Software developed by BROSHTech - no 0317 7676560)</p>
    </div>

    <div class="fbr-section text-center">
        @if($invoice->fbrLog && $invoice->fbrLog->status === 'success')
            <div class="font-bold" style="font-size: 12px;">FBR Invoice #:</div>
            <div class="fbr-id" style="font-size: 10px; word-break: break-all;">
                {{ $invoice->fbrLog->response['InvoiceNumber'] ?? '---' }}
            </div>
            
            @if(isset($invoice->fbrLog->response['InvoiceNumber']))
                <img class="qr-code" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($invoice->fbrLog->response['InvoiceNumber']) }}" alt="FBR QR" width="140" height="140">
            @endif
            
            <div style="margin-top: 10px;">
                <img src="https://fbr.gov.pk/fbr-logo.png" alt="FBR POS" style="width: 80px; filter: grayscale(1);">
                <div class="font-bold" style="font-size: 10px; margin-top: 5px;">FBR POS INVOICED</div>
            </div>
            
            <p style="font-size: 9px; margin-top: 10px; color: #000; font-weight: bold;">
                Verified by FBR TaxAsaan
            </p>
        @else
            <div style="border: 1px solid #000; padding: 10px; margin: 10px 0;">
                <div class="font-bold" style="font-size: 14px; color: #000;">FBR SYNC PENDING</div>
                <div style="font-size: 10px; margin-top: 5px;">This invoice will be synced once offline issues are resolved.</div>
            </div>
        @endif
        
        <p style="font-size: 8px; margin-top: 10px;">
            Verify this invoice through FBR TaxAsaan Mobile App or<br>
            SMS at 9966 and win exciting prizes in lottery.
        </p>
    </div>

    <div class="no-print text-center" style="margin-top: 20px;">
        <button onclick="window.print()" style="background:#000; color:#fff; padding:10px 20px; border:none; cursor:pointer; font-weight:bold; margin-bottom: 5px;">PRINT THERMAL RECEIPT</button>
        <button onclick="window.close()" style="background:#ef4444; color:#fff; padding:10px 20px; border:none; cursor:pointer; font-weight:bold;">CLOSE WINDOW</button>
        <br><br>
        <a href="{{ route('pos.index') }}" style="color:#666; font-size:10px; text-decoration:none;">Back to POS</a>
    </div>
</div>

<script>
    // Automatically trigger the browser print dialog when the receipt renders
    window.onload = function() {
        setTimeout(() => {
            window.print();
        }, 500); // Slight delay ensures styles and QR codes are fully painted
    };
</script>
</body>
</html>

