<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FBR Payload — {{ $log->invoice_no }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold: #F7DF79;
            --dark: #111827;
            --gray: #6b7280;
            --light: #f9fafb;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Outfit', sans-serif; background: #f3f4f6; color: var(--dark); min-height: 100vh; }

        /* ── Header ── */
        .page-header {
            background: var(--dark);
            color: #fff;
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .page-header-brand { display: flex; align-items: center; gap: 12px; }
        .brand-logo { width: 36px; height: 36px; background: var(--gold); border-radius: 10px; display: flex; align-items: center; justify-content: center; transform: rotate(45deg); flex-shrink: 0; }
        .brand-logo span { transform: rotate(-45deg); font-weight: 900; font-size: 16px; color: var(--dark); }
        .brand-name { font-weight: 800; font-size: 18px; }
        .header-badge { background: rgba(247,223,121,.15); border: 1px solid rgba(247,223,121,.3); color: var(--gold); font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: .12em; padding: 4px 10px; border-radius: 20px; }

        /* ── Layout ── */
        .page-wrap { max-width: 1100px; margin: 0 auto; padding: 40px 24px; }

        /* ── Status Bar ── */
        .status-bar {
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;
            background: #fff; border-radius: 20px; padding: 20px 28px;
            box-shadow: 0 2px 12px rgba(0,0,0,.05); margin-bottom: 28px;
            border-left: 5px solid var(--gold);
        }
        .status-bar-left { display: flex; flex-direction: column; gap: 4px; }
        .status-bar-inv { font-size: 22px; font-weight: 900; }
        .status-bar-meta { font-size: 12px; color: var(--gray); }
        .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: .06em; }
        .status-simulated { background: #fef9c3; color: #92400e; }
        .status-success { background: #dcfce7; color: #166534; }
        .dot-pulse::before { content: ''; display: inline-block; width: 8px; height: 8px; background: currentColor; border-radius: 50%; margin-right: 4px; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

        /* ── Grid ── */
        .content-grid { display: grid; grid-template-columns: 1fr 1.6fr; gap: 24px; }
        @media (max-width: 768px) { .content-grid { grid-template-columns: 1fr; } }

        /* ── Cards ── */
        .card { background: #fff; border-radius: 20px; box-shadow: 0 2px 12px rgba(0,0,0,.05); overflow: hidden; margin-bottom: 24px; }
        .card-head { padding: 18px 24px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 10px; }
        .card-head-icon { width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .card-head-title { font-size: 14px; font-weight: 800; }
        .card-head-sub { font-size: 11px; color: var(--gray); }
        .card-body { padding: 20px 24px; }

        /* ── Detail Rows ── */
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 11px 0; border-bottom: 1px solid #f9fafb; }
        .detail-row:last-child { border-bottom: none; }
        .detail-key { font-size: 11px; font-weight: 700; color: var(--gray); text-transform: uppercase; letter-spacing: .06em; }
        .detail-val { font-size: 13px; font-weight: 700; color: var(--dark); text-align: right; max-width: 200px; word-break: break-all; }

        /* ── Items Table ── */
        .items-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .items-table th { font-size: 10px; font-weight: 900; color: var(--gray); text-transform: uppercase; letter-spacing: .06em; padding: 10px 14px; text-align: left; border-bottom: 2px solid #f3f4f6; }
        .items-table td { padding: 12px 14px; border-bottom: 1px solid #fafafa; vertical-align: middle; }
        .items-table tr:last-child td { border-bottom: none; }
        .items-table tbody tr:hover td { background: #fffbeb; }

        /* ── JSON Block ── */
        .json-wrap { background: #0f172a; border-radius: 0 0 20px 20px; padding: 24px; overflow-x: auto; }
        .json-code { font-family: 'JetBrains Mono', monospace; font-size: 12px; line-height: 1.8; white-space: pre; color: #e2e8f0; }
        .json-key { color: #7dd3fc; }
        .json-string { color: #86efac; }
        .json-number { color: #fde68a; }

        /* ── Response Box ── */
        .response-box { background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 14px; padding: 16px 20px; }
        .response-code { font-size: 20px; font-weight: 900; color: #166534; }
        .response-msg { font-size: 12px; color: #15803d; margin-top: 2px; }

        /* ── Totals ── */
        .total-highlight { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; background: var(--dark); color: #fff; border-radius: 14px; margin-top: 16px; }
        .total-highlight-label { font-size: 13px; font-weight: 700; opacity: .7; }
        .total-highlight-val { font-size: 22px; font-weight: 900; }

        /* ── Print ── */
        .no-print-btn { display: flex; gap: 12px; margin-top: 32px; flex-wrap: wrap; }
        .btn-gold { background: var(--gold); color: var(--dark); font-weight: 700; padding: 12px 24px; border-radius: 12px; border: none; cursor: pointer; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all .2s; font-family: 'Outfit', sans-serif; }
        .btn-gold:hover { background: #fde047; transform: translateY(-1px); }
        .btn-ghost { background: #fff; color: var(--dark); font-weight: 700; padding: 12px 24px; border-radius: 12px; border: 1.5px solid #e5e7eb; cursor: pointer; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all .2s; font-family: 'Outfit', sans-serif; }
        .btn-ghost:hover { border-color: var(--gold); background: #fffbeb; }
        @media print { .no-print-btn { display: none; } .page-header { position: relative; } body { background: #fff; } }
    </style>
</head>
<body>

<header class="page-header">
    <div class="page-header-brand">
        <div class="brand-logo"><span>S</span></div>
        <span class="brand-name">The Crimpers</span>
    </div>
    <span class="header-badge">FBR Payload Inspector</span>
</header>

<div class="page-wrap">

    {{-- Status Bar --}}
    <div class="status-bar">
        <div class="status-bar-left">
            <div class="status-bar-inv">Invoice: {{ $log->invoice_no }}</div>
            <div class="status-bar-meta">
                Checkout at {{ $log->created_at->format('d M Y, H:i:s') }}
                &nbsp;·&nbsp;
                USIN: <strong>{{ $log->payload['USIN'] ?? '—' }}</strong>
            </div>
        </div>
        <span class="status-badge status-{{ $log->status }} dot-pulse">{{ ucfirst($log->status) }}</span>
    </div>

    <div class="content-grid">

        {{-- Left Column --}}
        <div>

            {{-- Transaction Summary --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-head-icon" style="background:#fef9c3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div>
                        <div class="card-head-title">Transaction Details</div>
                        <div class="card-head-sub">Sent to FBR API</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="detail-row"><span class="detail-key">Invoice No</span><span class="detail-val">{{ $log->payload['InvoiceNumber'] }}</span></div>
                    <div class="detail-row"><span class="detail-key">POS ID</span><span class="detail-val">{{ $log->payload['POSID'] }}</span></div>
                    <div class="detail-row"><span class="detail-key">USIN</span><span class="detail-val" style="font-family:monospace;font-size:11px;color:#7c3aed">{{ $log->payload['USIN'] }}</span></div>
                    <div class="detail-row"><span class="detail-key">Date & Time</span><span class="detail-val">{{ $log->payload['DateTime'] }}</span></div>
                    <div class="detail-row"><span class="detail-key">Buyer Name</span><span class="detail-val">{{ $log->payload['BuyerName'] }}</span></div>
                    <div class="detail-row"><span class="detail-key">Payment Mode</span>
                        <span class="detail-val">
                            {{ $log->payload['PaymentMode'] == 1 ? 'Cash (Code: 1)' : 'Card/QR (Code: 2)' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Financials --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-head-icon" style="background:#dbeafe">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2.5"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div>
                        <div class="card-head-title">Financial Breakdown</div>
                        <div class="card-head-sub">Tax & discount data</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="detail-row"><span class="detail-key">Sale Value</span><span class="detail-val">PKR {{ number_format($log->payload['TotalSaleValue'], 2) }}</span></div>
                    <div class="detail-row"><span class="detail-key">Tax Charged (5%)</span><span class="detail-val" style="color:#1d4ed8">PKR {{ number_format($log->payload['TotalTaxCharged'], 2) }}</span></div>
                    <div class="detail-row"><span class="detail-key">Discount Applied</span><span class="detail-val" style="color:#dc2626">-PKR {{ number_format($log->payload['Discount'], 2) }}</span></div>
                    <div class="total-highlight">
                        <span class="total-highlight-label">Net Payable to FBR</span>
                        <span class="total-highlight-val">PKR {{ number_format($log->payload['TotalSaleValue'], 0) }}</span>
                    </div>
                </div>
            </div>

            {{-- FBR Response --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-head-icon" style="background:#dcfce7">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#166534" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div>
                        <div class="card-head-title">Simulated FBR Response</div>
                        <div class="card-head-sub">What FBR API returns</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="response-box">
                        <div class="response-code">Code 100 ✓</div>
                        <div class="response-msg">Successfully Simulated for Testing</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div>

            {{-- Items --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-head-icon" style="background:#f3e8ff">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2.5"><path d="m6 3 6 6 6-6"/><path d="M20 21H4"/><path d="m6 21 6-6 6 6"/></svg>
                    </div>
                    <div>
                        <div class="card-head-title">Services / Items</div>
                        <div class="card-head-sub">{{ count($log->payload['Items'] ?? []) }} item(s) in this transaction</div>
                    </div>
                </div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Service / Item Name</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($log->payload['Items'] ?? [] as $item)
                        <tr>
                            <td><code style="font-size:11px;color:#6b7280">{{ $item['ItemCode'] }}</code></td>
                            <td><strong>{{ $item['ItemName'] }}</strong></td>
                            <td>{{ $item['Quantity'] }}</td>
                            <td>PKR {{ number_format($item['SaleValue'], 0) }}</td>
                            <td><strong>PKR {{ number_format($item['TotalAmount'], 0) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Full Raw JSON --}}
            <div class="card" style="margin-bottom:0">
                <div class="card-head">
                    <div class="card-head-icon" style="background:#e0f2fe">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                    </div>
                    <div>
                        <div class="card-head-title">Raw JSON Payload</div>
                        <div class="card-head-sub">Exact data sent to FBR PRAL API</div>
                    </div>
                </div>
                <div class="json-wrap">
                    <div class="json-code" id="json-output"></div>
                </div>
            </div>
        </div>

    </div>

    {{-- Action Buttons --}}
    <div class="no-print-btn">
        <button onclick="window.print()" class="btn-gold">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
            Print This Page
        </button>
        <a href="{{ route('fbr.index') }}" class="btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            All FBR Logs
        </a>
        <a href="{{ route('pos.index') }}" class="btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            Back to POS
        </a>
    </div>

</div>

<script>
const payload = {!! json_encode($log->payload) !!};
function syntaxHighlight(json) {
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        let cls = 'json-number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) cls = 'json-key';
            else cls = 'json-string';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}
document.getElementById('json-output').innerHTML = syntaxHighlight(JSON.stringify(payload, null, 2));
</script>
</body>
</html>
