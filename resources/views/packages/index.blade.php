@extends('layouts.app')

@section('content')
<style>
.pg-wrap{font-family:'Outfit',sans-serif;display:flex;flex-direction:column;gap:20px}
/* Header */
.pg-header{display:flex;align-items:center;justify-content:space-between}
.pg-title{font-size:22px;font-weight:900;color:#111827;margin:0}
.pg-sub{font-size:13px;color:#9ca3af;margin:2px 0 0}
.btn-dark{display:inline-flex;align-items:center;gap:8px;background:#111827;border:none;border-radius:14px;padding:10px 20px;font-size:13px;font-weight:700;color:#fff;cursor:pointer;text-decoration:none;box-shadow:0 4px 12px rgba(17,24,39,.25);transition:all .2s}
.btn-dark:hover{background:#1f2937;transform:translateY(-1px)}
.btn-dark svg{width:16px;height:16px}
/* Stats Strip */
.stats-strip{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.stat-card{border-radius:16px;border:1px solid;padding:16px 18px;display:flex;align-items:center;gap:14px}
.stat-card.yellow{background:#fef9c3;border-color:#fef08a}
.stat-card.purple{background:#f3e8ff;border-color:#e9d5ff}
.stat-card.cyan{background:#cffafe;border-color:#a5f3fc}
.stat-icon{width:40px;height:40px;background:#fff;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 1px 4px rgba(0,0,0,.08)}
.stat-icon svg{width:18px;height:18px}
.stat-icon.yellow svg{color:#ca8a04}
.stat-icon.purple svg{color:#9333ea}
.stat-icon.cyan svg{color:#0891b2}
.stat-label{font-size:9px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px}
.stat-card.yellow .stat-label{color:#92400e}
.stat-card.purple .stat-label{color:#7c3aed}
.stat-card.cyan .stat-label{color:#0e7490}
.stat-val{font-size:22px;font-weight:900;color:#111827}
/* Grid */
.pkg-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:18px}
/* Card */
.pkg-card{background:#fff;border-radius:20px;border:1.5px solid #f1f1f1;box-shadow:0 1px 6px rgba(0,0,0,.05);overflow:hidden;transition:all .2s}
.pkg-card:hover{border-color:#e9d5ff;box-shadow:0 8px 28px rgba(168,85,247,.15);transform:translateY(-2px)}
.pkg-card-accent{height:4px;background:linear-gradient(90deg,#a855f7,#7c3aed)}
.pkg-card-body{padding:20px}
.pkg-card-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px}
.pkg-icon{width:50px;height:50px;background:#f3e8ff;border-radius:16px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.pkg-icon svg{width:24px;height:24px;color:#9333ea}
.pkg-actions{display:flex;gap:6px}
.act-btn{width:30px;height:30px;border-radius:10px;border:none;background:#f3f4f6;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s}
.act-btn svg{width:13px;height:13px;color:#9ca3af}
.act-btn.edit:hover{background:#dbeafe}
.act-btn.edit:hover svg{color:#3b82f6}
.act-btn.del:hover{background:#fee2e2}
.act-btn.del:hover svg{color:#ef4444}
.pkg-name{font-size:16px;font-weight:900;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:4px}
.pkg-desc{font-size:12px;color:#9ca3af;line-height:1.5;margin-bottom:14px;min-height:36px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
/* Services list */
.pkg-services{display:flex;flex-direction:column;gap:6px;margin-bottom:16px}
.pkg-svc-row{display:flex;align-items:center;gap:8px;font-size:12px;color:#374151}
.pkg-svc-check{width:16px;height:16px;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.pkg-svc-check svg{width:9px;height:9px;color:#16a34a}
.pkg-more{font-size:10px;color:#9ca3af;font-weight:700;text-transform:uppercase;letter-spacing:.06em;padding-left:24px}
/* Footer */
.pkg-footer{display:flex;align-items:center;justify-content:space-between;padding-top:14px;border-top:1px solid #f3f4f6}
.pkg-price-wrap{}
.pkg-price-label{font-size:9px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px}
.pkg-price{font-size:22px;font-weight:900;color:#111827}
.pkg-price span{font-size:12px;font-weight:900;color:#9333ea;margin-right:2px}
.pkg-dur-wrap{text-align:right}
.pkg-dur-label{font-size:9px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px}
.pkg-dur{font-size:16px;font-weight:900;color:#374151}
.pkg-dur small{font-size:11px;font-weight:600;color:#9ca3af}
/* Empty */
.empty-state{grid-column:1/-1;background:#fff;border-radius:20px;border:2px dashed #e5e7eb;padding:60px 20px;display:flex;flex-direction:column;align-items:center;gap:14px}
.empty-icon{width:60px;height:60px;background:#f3e8ff;border-radius:50%;display:flex;align-items:center;justify-content:center}
.empty-icon svg{width:28px;height:28px;color:#9333ea}
.empty-text{font-size:14px;color:#9ca3af;font-weight:500}
</style>

<div class="pg-wrap">
    {{-- Header --}}
    <div class="pg-header">
        <div>
            <h2 class="pg-title">Service Packages</h2>
            <p class="pg-sub">Bundle services into attractive promotional packages.</p>
        </div>
        {{-- <a href="{{ route('packages.create') }}" class="btn-dark">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            New Package
        </a> --}}
    </div>

    {{-- Stats Strip --}}
    <div class="stats-strip">
        <div class="stat-card purple">
            <div class="stat-icon purple">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/></svg>
            </div>
            <div>
                <div class="stat-label">Total Packages</div>
                <div class="stat-val">{{ $packages->total() }}</div>
            </div>
        </div>
        <div class="stat-card yellow">
            <div class="stat-icon yellow">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 3 6 6 6-6"/><path d="M20 21H4"/><path d="m6 21 6-6 6 6"/></svg>
            </div>
            <div>
                <div class="stat-label">Avg Services</div>
                <div class="stat-val">{{ $packages->count() > 0 ? number_format($packages->sum(fn($p) => $p->services->count()) / $packages->count(), 1) : '0' }}</div>
            </div>
        </div>
        <div class="stat-card cyan">
            <div class="stat-icon cyan">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <div class="stat-label">Avg Duration</div>
                <div class="stat-val">{{ $packages->count() > 0 ? number_format($packages->avg('duration'), 0) : '0' }}<small style="font-size:13px;color:#9ca3af;font-weight:600"> min</small></div>
            </div>
        </div>
    </div>

    {{-- Grid --}}
    <div class="pkg-grid">
        @forelse($packages as $package)
        <div class="pkg-card">
            <div class="pkg-card-accent"></div>
            <div class="pkg-card-body">
                <div class="pkg-card-top">
                    <div class="pkg-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                    </div>
                    <div class="pkg-actions">
                        <a href="{{ route('packages.edit', $package) }}" class="act-btn edit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                        </a>
                        <button type="button" onclick="confirmItemDeletion('{{ route('packages.destroy', $package) }}')" class="act-btn del">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        </button>
                    </div>
                </div>

                <div class="pkg-name">{{ $package->name }}</div>
                <div class="pkg-desc">{{ $package->description ?? 'No description provided for this package.' }}</div>

                <div class="pkg-services">
                    @foreach($package->services->take(3) as $svc)
                    <div class="pkg-svc-row">
                        <div class="pkg-svc-check">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                        </div>
                        <span>{{ $svc->name }}</span>
                    </div>
                    @endforeach
                    @if($package->services->count() > 3)
                    <div class="pkg-more">+{{ $package->services->count() - 3 }} more services</div>
                    @endif
                </div>

                <div class="pkg-footer">
                    <div class="pkg-price-wrap">
                        <div class="pkg-price-label">Package Price</div>
                        <div class="pkg-price"><span>PKR</span>{{ number_format($package->price, 0) }}</div>
                    </div>
                    <div class="pkg-dur-wrap">
                        <div class="pkg-dur-label">Duration</div>
                        <div class="pkg-dur">{{ $package->duration }}<small> min</small></div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
            </div>
            <p class="empty-text">No packages yet. Create your first bundle.</p>
            <a href="{{ route('packages.create') }}" class="btn-dark">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Create Package
            </a>
        </div>
        @endforelse
    </div>

    <div>{{ $packages->links('pagination::tailwind') }}</div>
</div>

{{-- Global Delete Modal --}}
<div id="global-delete-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(6px);z-index:9999;align-items:center;justify-content:center;font-family:'Outfit',sans-serif">
    <div style="background:#fff;border-radius:24px;width:100%;max-width:380px;overflow:hidden;box-shadow:0 30px 80px rgba(0,0,0,.2);margin:20px">
        <div style="height:4px;background:linear-gradient(90deg,#ef4444,#f87171)"></div>
        <div style="padding:32px;text-align:center">
            <div style="width:60px;height:60px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
            </div>
            <h3 style="font-size:20px;font-weight:900;color:#111827;margin:0 0 8px">Delete Package?</h3>
            <p style="font-size:13px;color:#9ca3af;font-weight:400;line-height:1.6;margin:0 0 28px">This will permanently remove this package bundle. Individual services will remain intact.</p>
            <form id="global-delete-form" method="POST" style="display:flex;gap:10px;margin:0">
                @csrf @method('DELETE')
                <button type="button" onclick="closeGlobalDelete()" style="flex:1;padding:12px;border-radius:12px;border:1.5px solid #e5e7eb;background:#fff;cursor:pointer;font-weight:700;font-size:13px;font-family:'Outfit',sans-serif;color:#374151;transition:all .2s" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">Cancel</button>
                <button type="submit" style="flex:1;padding:12px;border-radius:12px;border:none;background:#ef4444;color:#fff;cursor:pointer;font-weight:700;font-size:13px;font-family:'Outfit',sans-serif;transition:all .2s;box-shadow:0 4px 12px rgba(239,68,68,.3)" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">Yes, Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmItemDeletion(url) {
        document.getElementById('global-delete-form').action = url;
        document.getElementById('global-delete-overlay').style.display = 'flex';
    }
    function closeGlobalDelete() {
        document.getElementById('global-delete-overlay').style.display = 'none';
        document.getElementById('global-delete-form').action = '';
    }
</script>
@endsection
