@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Inventory Dashboard</h2>
            <p class="text-gray-500 mt-1">Monitor stock levels, value, and product performance.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('purchases.index') }}" class="bg-primary-gold px-6 py-2 rounded-xl shadow-md text-sm font-bold text-black hover:bg-yellow-400 transition-all flex items-center space-x-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Order Stock</span>
            </a>
        </div>
    </div>

    <!-- Inventory Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 group">
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Total Stock Value</p>
            <h3 class="text-2xl font-black text-gray-900">PKR {{ number_format($totalInventoryValue, 2) }}</h3>
            <p class="text-[10px] text-gray-400 mt-2 font-medium">Across {{ $totalProducts }} products</p>
        </div>
        <div class="bg-card-yellow p-6 rounded-[2.5rem] shadow-sm border border-yellow-100 group">
            <p class="text-[10px] font-extrabold text-yellow-700 uppercase tracking-widest mb-1">Retail Value</p>
            <h3 class="text-2xl font-black text-gray-900">PKR {{ number_format($totalRetailValue, 2) }}</h3>
            <p class="text-[10px] text-yellow-600 mt-2 font-medium">Potential revenue</p>
        </div>
        <div class="bg-card-purple p-6 rounded-[2.5rem] shadow-sm border border-purple-100 group">
            <p class="text-[10px] font-extrabold text-purple-700 uppercase tracking-widest mb-1">Low Stock Alerts</p>
            <h3 class="text-2xl font-black text-gray-900">{{ $lowStockProducts->count() }}</h3>
            <p class="text-[10px] text-purple-600 mt-2 font-medium">Require attention</p>
        </div>
        <div class="bg-card-cyan p-6 rounded-[2.5rem] shadow-sm border border-cyan-100 group">
            <p class="text-[10px] font-extrabold text-cyan-700 uppercase tracking-widest mb-1">Out of Stock</p>
            <h3 class="text-2xl font-black text-gray-900">{{ $outOfStockProducts->count() }}</h3>
            <p class="text-[10px] text-cyan-600 mt-2 font-medium">Immediate restock needed</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Low Stock Items -->
        <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <h4 class="text-lg font-bold text-gray-800">Low Stock Items</h4>
                <a href="{{ route('inventory.low-stock') }}" class="text-xs font-bold text-primary-gold hover:underline">View All</a>
            </div>
            <div class="space-y-4">
                @foreach($lowStockProducts as $product)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-transparent hover:border-gray-100 hover:bg-white hover:shadow-md transition-all">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-gray-400">
                            <i data-lucide="package" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $product->name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">Current: {{ $product->current_stock }} / Min: {{ $product->min_stock_level }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Used Products -->
        <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
            <h4 class="text-lg font-bold text-gray-800 mb-8">Top Consumption (Month)</h4>
            <div class="space-y-6">
                @foreach($topUsedProducts as $usage)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center">
                            <i data-lucide="flask-conical" class="w-5 h-5 text-purple-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $usage->product->name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">Service consumption</p>
                        </div>
                    </div>
                    <span class="text-sm font-black text-gray-800">{{ $usage->total_used }} units</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
