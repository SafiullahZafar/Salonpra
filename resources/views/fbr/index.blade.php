@extends('layouts.app')

@section('content')
<style>
/* ── FBR Page Styles ── */
.fbr-stat-card { background:#fff; border-radius:20px; border:1px solid #f1f1f1; box-shadow:0 2px 10px rgba(0,0,0,.05); padding:24px; display:flex; align-items:center; gap:16px; }
.fbr-stat-icon { width:52px; height:52px; border-radius:16px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.fbr-stat-label { font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.08em; }
.fbr-stat-value { font-size:28px; font-weight:900; color:#111827; }
.fbr-table { width:100%; border-collapse:collapse; }
.fbr-table th { font-size:10px; font-weight:900; color:#9ca3af; text-transform:uppercase; letter-spacing:.08em; padding:12px 16px; text-align:left; border-bottom:2px solid #f3f4f6; }
.fbr-table td { padding:14px 16px; font-size:13px; border-bottom:1px solid #f9fafb; vertical-align:middle; }
.fbr-table tr:hover td { background:#fafafa; }
.status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:900; text-transform:uppercase; letter-spacing:.05em; }
.status-simulated { background:#fef9c3; color:#92400e; }
.status-success { background:#dcfce7; color:#166534; }
.status-failed { background:#fee2e2; color:#991b1b; }
.json-panel { background:#111827; color:#d1fae5; border-radius:16px; padding:24px; font-family:'Courier New',monospace; font-size:12px; line-height:1.7; overflow-x:auto; margin-top:0; }
.json-key { color:#93c5fd; }
.json-string { color:#86efac; }
.json-number { color:#fde68a; }
.modal-bg { position:fixed; inset:0; background:rgba(17,24,39,.7); backdrop-filter:blur(8px); z-index:50; display:flex; align-items:center; justify-content:center; padding:24px; }
.modal-inner { background:#fff; border-radius:24px; width:100%; max-width:680px; max-height:85vh; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 30px 80px rgba(0,0,0,.25); }
.modal-head { padding:24px 28px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; flex-shrink:0; }
.modal-body { flex:1; overflow-y:auto; padding:24px 28px; }
</style>

<div class="max-w-6xl mx-auto">
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h1 class="text-3xl font-black text-gray-900">FBR Integration</h1>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-black rounded-full uppercase tracking-widest">Testing Mode</span>
            </div>
            <p class="text-gray-500">Simulated POS checkout payloads in the FBR-required format. Ready for live API connection.</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 rounded-lg text-sm font-semibold">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            Simulation Active
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="fbr-stat-card">
            <div class="fbr-stat-icon bg-yellow-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div>
                <div class="fbr-stat-label">Total Logs</div>
                <div class="fbr-stat-value">{{ $totalSimulated }}</div>
            </div>
        </div>
        <div class="fbr-stat-card">
            <div class="fbr-stat-icon bg-blue-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
            </div>
            <div>
                <div class="fbr-stat-label">Logged Today</div>
                <div class="fbr-stat-value">{{ $todayCount }}</div>
            </div>
        </div>
        <div class="fbr-stat-card">
            <div class="fbr-stat-icon bg-green-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div>
                <div class="fbr-stat-label">API Status</div>
                <div class="fbr-stat-value text-green-500 text-xl">Ready</div>
            </div>
        </div>
    </div>

    {{-- FBR Info Banner --}}
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 mb-8 flex gap-4">
        <svg class="flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
        <div>
            <p class="font-bold text-blue-800 text-sm">About FBR POS Integration</p>
            <p class="text-blue-600 text-xs mt-1">
                This page captures the exact payload that would be transmitted to the <strong>Federal Board of Revenue (FBR) PRAL API</strong> on every checkout. Fields like <code class="bg-blue-100 px-1 rounded">USIN</code>, <code class="bg-blue-100 px-1 rounded">POSID</code>, and item-level tax breakdowns follow the official FBR POS Integration Technical Manual.
                When you're ready to go live, simply configure your <strong>FBR credentials</strong> (POSID, POS Username, POS Password) in the settings panel and flip the switch from Simulation to Live.
            </p>
        </div>
    </div>

    {{-- Logs Table --}}
    <div class="salon-card p-0 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Checkout Payload Logs</h2>
            <span class="text-xs text-gray-400 font-semibold">Click any row to inspect the full JSON payload</span>
        </div>

        @if($logs->count() === 0)
        <div class="flex flex-col items-center justify-center py-20 text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <p class="mt-3 font-semibold text-sm">No FBR logs yet.</p>
            <p class="text-xs">Complete a checkout in the POS Terminal to generate your first entry.</p>
        </div>
        @else
        <table class="fbr-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Invoice No</th>
                    <th>USIN</th>
                    <th>Buyer</th>
                    <th>Total</th>
                    <th>Tax</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Logged At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr onclick="showPayload({{ $log->id }})" class="cursor-pointer">
                    <td class="font-semibold text-gray-400">{{ $loop->iteration }}</td>
                    <td><span class="font-bold text-gray-800">{{ $log->invoice_no }}</span></td>
                    <td><code class="text-xs text-purple-600 bg-purple-50 px-2 py-0.5 rounded">{{ $log->payload['USIN'] ?? '—' }}</code></td>
                    <td class="text-gray-600">{{ $log->payload['BuyerName'] ?? 'Walk-in' }}</td>
                    <td><strong>PKR {{ number_format($log->payload['TotalSaleValue'] ?? 0, 0) }}</strong></td>
                    <td class="text-blue-600">PKR {{ number_format($log->payload['TotalTaxCharged'] ?? 0, 0) }}</td>
                    <td>
                        <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                            {{ $log->payload['PaymentMode'] == 1 ? 'Cash' : 'Card/QR' }}
                        </span>
                    </td>
                    <td><span class="status-badge status-{{ $log->status }}">{{ ucfirst($log->status) }}</span></td>
                    <td class="text-gray-400 text-xs">{{ $log->created_at->format('d M, H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-50">{{ $logs->links() }}</div>
        @endif
        @endif
    </div>
</div>

{{-- JSON Payload Modal --}}
<div id="payload-modal" class="modal-bg" style="display:none" onclick="if(event.target===this) closeModal()">
    <div class="modal-inner">
        <div class="modal-head">
            <div>
                <div class="text-lg font-black text-gray-900">FBR Payload Inspector</div>
                <div id="modal-inv-no" class="text-sm text-gray-400 font-semibold mt-0.5"></div>
            </div>
            <button onclick="closeModal()" class="text-gray-300 hover:text-gray-900 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-xs font-bold px-3 py-1.5 bg-gray-100 rounded-lg text-gray-500 uppercase tracking-widest">POST → FBR PRAL API</span>
                <span class="text-xs font-mono text-gray-400">application/json</span>
            </div>
            <div class="json-panel" id="payload-content">...</div>

            <div class="mt-6 p-4 bg-green-50 rounded-xl border border-green-100">
                <div class="text-xs font-black text-green-700 uppercase tracking-widest mb-1">Simulated FBR Response</div>
                <div class="font-mono text-sm text-green-800" id="modal-response">...</div>
            </div>
        </div>
    </div>
</div>

@php
$logsJson = $logs->map(fn($l) => [
    'id'       => $l->id,
    'inv_no'   => $l->invoice_no,
    'payload'  => $l->payload,
    'response' => $l->response
])->values()->toJson();
@endphp

<script>
const logsData = {!! $logsJson !!};

function showPayload(id) {
    const log = logsData.find(l => l.id === id);
    if (!log) return;
    document.getElementById('payload-modal').style.display = 'flex';
    document.getElementById('modal-inv-no').innerText = 'Invoice: ' + log.inv_no;
    document.getElementById('payload-content').innerHTML = syntaxHighlight(JSON.stringify(log.payload, null, 2));
    document.getElementById('modal-response').innerText = log.response || '—';
}

function closeModal() {
    document.getElementById('payload-modal').style.display = 'none';
}

function syntaxHighlight(json) {
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
        let cls = 'json-number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) cls = 'json-key';
            else cls = 'json-string';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endsection
