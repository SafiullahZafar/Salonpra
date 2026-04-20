@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Daily Cash Reconciliation</h2>
            <p class="text-gray-500 mt-1">Match recorded cash sales with actual cash in hand.</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-4 py-2 bg-white rounded-xl shadow-sm border border-gray-100 text-sm font-bold text-gray-800">
                {{ now()->format('l, d M Y') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
        <!-- Input Form -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100">
                <form action="{{ route('reconciliation.store') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest ml-1">Opening Balance</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400 group-focus-within:text-primary-gold transition-colors">
                                    <i data-lucide="wallet" class="w-4 h-4"></i>
                                </div>
                                <input type="number" step="0.01" name="opening_balance" value="{{ $reconciliation->opening_balance ?? '' }}" class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 pl-16 pr-6 focus:ring-2 focus:ring-primary-gold transition-all duration-300 font-bold" placeholder="0.00">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest ml-1">Expected Cash Sales</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400">
                                    <i data-lucide="calculator" class="w-4 h-4"></i>
                                </div>
                                <input type="number" step="0.01" name="expected_cash" value="{{ $totalSales }}" readonly class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 pl-16 pr-6 font-bold text-gray-400" placeholder="0.00">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 italic pl-1">Automatically calculated from today's cash invoices.</p>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest ml-1">Actual Cash In Drawer</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400 group-focus-within:text-green-500 transition-colors">
                                    <i data-lucide="banknote" class="w-4 h-4"></i>
                                </div>
                                <input type="number" step="0.01" name="actual_cash" value="{{ $reconciliation->actual_cash ?? '' }}" class="w-full bg-gray-50 border-gray-100 rounded-2xl py-5 pl-16 pr-6 focus:ring-2 focus:ring-green-500 transition-all duration-300 text-xl font-black" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 bg-primary-gold rounded-2xl shadow-lg text-sm font-black text-black hover:bg-yellow-400 hover:shadow-xl transition-all flex items-center justify-center space-x-3">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            <span>Save Reconciliation Record</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Status Card -->
        <div class="space-y-8">
            <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gray-50 rounded-bl-[4rem]"></div>
                
                <h4 class="text-lg font-bold text-gray-800 mb-8">Reconciliation Status</h4>
                
                @if($reconciliation)
                <div class="space-y-8">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Difference</span>
                        <span class="text-2xl font-black {{ $reconciliation->difference >= 0 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $reconciliation->difference >= 0 ? '+' : '' }}PKR {{ number_format($reconciliation->difference, 2) }}
                        </span>
                    </div>
                    
                    <div class="p-6 rounded-3xl {{ $reconciliation->status === 'matched' ? 'bg-green-50 border-green-100 text-green-700' : 'bg-red-50 border-red-100 text-red-700' }} border text-center">
                        <i data-lucide="{{ $reconciliation->status === 'matched' ? 'check-circle' : 'alert-circle' }}" class="w-12 h-12 mx-auto mb-4"></i>
                        <p class="text-sm font-black uppercase tracking-widest">{{ strtoupper($reconciliation->status) }}</p>
                        <p class="text-[10px] mt-2 opacity-80">{{ $reconciliation->status === 'matched' ? 'Cash drawer matches the system records.' : 'There is a discrepancy in the cash drawer.' }}</p>
                    </div>

                    <div class="pt-4 border-t border-gray-50 mt-4">
                        <div class="flex justify-between items-center text-xs font-bold text-gray-500">
                            <span>Recorded By</span>
                            <span class="text-gray-800">{{ $reconciliation->user->name ?? 'System Admin' }}</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="clock" class="w-8 h-8 text-gray-200"></i>
                    </div>
                    <p class="text-gray-400 font-bold">No record for today yet</p>
                    <p class="text-[10px] text-gray-300 mt-1 uppercase tracking-widest">Enter details to start</p>
                </div>
                @endif
            </div>

            <div class="bg-card-yellow p-8 rounded-[3rem] shadow-sm border border-yellow-100">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                        <i data-lucide="shield-check" class="w-5 h-5 text-yellow-600"></i>
                    </div>
                    <h5 class="text-sm font-bold text-gray-800 tracking-tight">Best Practices</h5>
                </div>
                <ul class="space-y-3">
                    <li class="text-[10px] text-yellow-800 font-medium flex items-start space-x-2">
                        <span class="w-1 h-1 bg-yellow-600 rounded-full mt-1.5 flex-shrink-0"></span>
                        <span>Reconcile cash drawer twice daily (Start/End).</span>
                    </li>
                    <li class="text-[10px] text-yellow-800 font-medium flex items-start space-x-2">
                        <span class="w-1 h-1 bg-yellow-600 rounded-full mt-1.5 flex-shrink-0"></span>
                        <span>Retain physical receipts for any discrepancies.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
