@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Inventory Procurement</h2>
            <p class="text-gray-500 mt-1">Manage purchase orders and stock replenishment from suppliers.</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <input type="text" placeholder="Search PO #..." class="bg-white border-gray-200 rounded-xl py-2 px-10 w-48 shadow-sm focus:ring-2 focus:ring-primary-gold transition-all duration-300">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            </div>
            <button class="bg-primary-gold px-6 py-2 rounded-xl shadow-md text-sm font-bold text-black hover:bg-yellow-400 transition-all flex items-center space-x-2">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>New Purchase</span>
            </button>
        </div>
    </div>

    <!-- Filters & Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                            <th class="px-8 py-5">PO Number</th>
                            <th class="px-8 py-5">Supplier</th>
                            <th class="px-8 py-5">Order Date</th>
                            <th class="px-8 py-5">Status</th>
                            <th class="px-8 py-5">Total</th>
                            <th class="px-8 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($purchases as $purchase)
                        <tr class="group hover:bg-gray-50 transition-colors">
                            <td class="px-8 py-6">
                                <span class="text-sm font-black text-gray-800 tracking-tighter">{{ $purchase->purchase_order_number }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-gray-600">{{ $purchase->supplier->name }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($purchase->order_date)->format('d M Y') }}</span>
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $statusStyles = [
                                        'ordered' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'received' => 'bg-green-50 text-green-600 border-green-100',
                                        'partially_received' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                        'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                                    ];
                                    $style = $statusStyles[$purchase->status] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                @endphp
                                <span class="px-3 py-1 {{ $style }} rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm">
                                    {{ str_replace('_', ' ', $purchase->status) }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm font-black text-gray-900">PKR {{ number_format($purchase->total_amount, 2) }}</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('purchases.show', $purchase) }}" class="p-2 text-gray-300 hover:text-primary-gold transition-all">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    @if($purchase->status === 'ordered')
                                    <a href="{{ route('purchases.receive', $purchase) }}" class="p-2 text-gray-300 hover:text-green-500 transition-all" title="Receive Stock">
                                        <i data-lucide="package-check" class="w-4 h-4"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                {{ $purchases->links() }}
            </div>
        </div>

        <!-- Sidebar Widgets -->
        <div class="space-y-6">
            <div class="bg-card-cyan p-8 rounded-[2.5rem] shadow-sm border border-cyan-100">
                <h4 class="text-lg font-bold text-gray-800 mb-4">Quick Stats</h4>
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] font-extrabold text-cyan-600 uppercase tracking-widest">Pending Orders</p>
                        <p class="text-3xl font-black text-gray-900 mt-1">4</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-cyan-600 uppercase tracking-widest">Spent this month</p>
                        <p class="text-2xl font-black text-gray-900 mt-1">PKR 2,150.40</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 mb-6 font-primary">Active Suppliers</h4>
                <div class="space-y-4">
                    @foreach($suppliers->take(5) as $supplier)
                    <div class="flex items-center space-x-4">
                        <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-[10px] font-black text-gray-400">
                            {{ substr($supplier->name, 0, 1) }}
                        </div>
                        <span class="text-xs font-bold text-gray-700">{{ $supplier->name }}</span>
                    </div>
                    @endforeach
                </div>
                <button class="w-full mt-6 text-[10px] font-extrabold text-primary-gold uppercase tracking-widest hover:underline text-left">Management Suppliers</button>
            </div>
        </div>
    </div>
</div>
@endsection
