@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Financial Overview Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Financial Intelligence</h2>
            <p class="text-gray-500 mt-1">Comprehensive breakdown of your salon's revenue and profitability.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 text-sm font-medium text-gray-600 hover:bg-gray-50 flex items-center space-x-2">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span>Export PDF</span>
            </button>
            <button class="bg-primary-gold px-6 py-2 rounded-xl shadow-md text-sm font-bold text-black hover:bg-yellow-400 transition-all flex items-center space-x-2">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span>Last 30 Days</span>
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <div class="bg-card-yellow p-6 rounded-[2rem] shadow-sm border border-yellow-100 relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-20 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="flex flex-col h-full justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm">
                        <i data-lucide="dollar-sign" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">PKR {{ number_format($totalRevenue, 2) }}</h3>
                    <div class="flex items-center mt-2 text-green-600 space-x-1">
                        <i data-lucide="arrow-up-right" class="w-3 h-3"></i>
                        <span class="text-[10px] font-bold">+12.5%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profit Margin -->
        <div class="bg-card-purple p-6 rounded-[2rem] shadow-sm border border-purple-100 relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-20 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="flex flex-col h-full justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm">
                        <i data-lucide="percent" class="w-6 h-6 text-purple-600"></i>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gross Margin</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($profitMargin, 1) }}%</h3>
                    <div class="flex items-center mt-2 text-purple-600 space-x-1">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        <span class="text-[10px] font-bold">Stable performance</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Customers -->
        <div class="bg-card-cyan p-6 rounded-[2rem] shadow-sm border border-cyan-100 relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-20 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="flex flex-col h-full justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm">
                        <i data-lucide="user-plus" class="w-6 h-6 text-cyan-600"></i>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">New Clients</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($newCustomers, 0) }}</h3>
                    <div class="flex items-center mt-2 text-cyan-600 space-x-1">
                        <i data-lucide="activity" class="w-3 h-3"></i>
                        <span class="text-[10px] font-bold">+{{ number_format($retentionRate, 1) }}% Retention</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gross Profit -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-gray-50 opacity-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="flex flex-col h-full justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center shadow-sm">
                        <i data-lucide="piggy-bank" class="w-6 h-6 text-gray-600"></i>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gross Profit</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">PKR {{ number_format($grossProfit, 2) }}</h3>
                    <p class="text-[10px] text-gray-400 mt-2 font-medium">After PKR {{ number_format($totalCost, 2) }} operating costs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Sales Velocity -->
        <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h4 class="text-lg font-bold text-gray-800">Sales Velocity (Today)</h4>
                    <p class="text-xs text-gray-400 font-medium">Tracking transction volume throughout the day</p>
                </div>
                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center">
                    <i data-lucide="bar-chart" class="w-5 h-5 text-primary-gold"></i>
                </div>
            </div>
            <div class="h-64">
                <canvas id="busyHoursChart"></canvas>
            </div>
        </div>

        <!-- Revenue Composition -->
        <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h4 class="text-lg font-bold text-gray-800">Revenue Composition</h4>
                    <p class="text-xs text-gray-400 font-medium">Share of Services vs Products</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-[10px] font-bold">Services</span>
                    <span class="px-3 py-1 bg-yellow-50 text-yellow-600 rounded-full text-[10px] font-bold">Products</span>
                </div>
            </div>
            <div class="flex items-center justify-around">
                <div class="relative w-48 h-48">
                    <canvas id="revenueShareChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-bold text-gray-800">Composition</span>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="flex flex-col">
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 bg-purple-500 rounded-full"></span>
                            <span class="text-sm font-bold text-gray-800">Services</span>
                        </div>
                        <span class="text-2xl font-extrabold text-gray-900 ml-5">PKR {{ number_format($serviceRevenue, 2) }}</span>
                        <span class="text-xs text-gray-400 ml-5">{{ $serviceRevenueShare }}% of total</span>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 bg-primary-gold rounded-full"></span>
                            <span class="text-sm font-bold text-gray-800">Products</span>
                        </div>
                        <span class="text-2xl font-extrabold text-gray-900 ml-5">PKR {{ number_format($productRevenue, 2) }}</span>
                        <span class="text-xs text-gray-400 ml-5">{{ $productRevenueShare }}% of total</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Performance & Top Items -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-8">
        <!-- Top Performing Services -->
        <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 col-span-2">
            <div class="flex items-center justify-between mb-8">
                <h4 class="text-lg font-bold text-gray-800">Top Performing Services</h4>
                <a href="{{ route('services.index') }}" class="text-xs font-bold text-primary-gold hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-4">
                            <th class="pb-4">Service Details</th>
                            <th class="pb-4">Bookings</th>
                            <th class="pb-4">Revenue</th>
                            <th class="pb-4 text-right">Performance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($topServices as $item)
                        <tr class="group hover:bg-gray-50 transition-colors">
                            <td class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-primary-gold bg-opacity-10 rounded-2xl flex items-center justify-center text-primary-gold">
                                        <i data-lucide="scissors" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $item['service']->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium">{{ $item['service']->category }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">
                                <span class="text-sm font-bold text-gray-800">{{ $item['quantity'] }} times</span>
                            </td>
                            <td class="py-4">
                                <span class="text-sm font-bold text-gray-800">PKR {{ number_format($item['revenue'], 2) }}</span>
                            </td>
                            <td class="py-4 text-right">
                                <div class="w-24 h-2 bg-gray-100 rounded-full ml-auto overflow-hidden">
                                    <div class="bg-primary-gold h-full rounded-full" style="width: {{ min(100, ($item['revenue'] / max(1, $totalRevenue)) * 500) }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Staff Performance -->
        <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
            <h4 class="text-lg font-bold text-gray-800 mb-8">Staff Upsell Power</h4>
            <div class="space-y-6">
                @foreach($staffPerformance as $staff)
                <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 hover:bg-primary-gold hover:bg-opacity-5 transition-all group">
                    <div class="flex items-center space-x-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($staff->name) }}&background=F7DF79&color=000" class="w-10 h-10 rounded-xl shadow-sm" alt="">
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $staff->name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">PKR {{ number_format($staff->upsellPerformance?->upsell_revenue ?? 0, 0) }} Upsell</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-900">{{ number_format($staff->upsellPerformance?->upsell_count ?? 0, 0) }} items</p>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 ml-auto group-hover:text-primary-gold"></i>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="w-full mt-8 py-3 rounded-2xl border-2 border-dashed border-gray-200 text-sm font-bold text-gray-400 hover:border-primary-gold hover:text-primary-gold transition-all">
                Staff Leaderboard
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Busy Hours Chart (Sales Velocity)
        const busyHoursCtx = document.getElementById('busyHoursChart').getContext('2d');
        const busyHoursData = @json($busyHours->values());
        
        new Chart(busyHoursCtx, {
            type: 'line',
            data: {
                labels: busyHoursData.map(d => d.hour + ':00'),
                datasets: [{
                    label: 'Transactions',
                    data: busyHoursData.map(d => d.invoice_count),
                    borderColor: '#F7DF79',
                    backgroundColor: 'rgba(247, 223, 121, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#F7DF79'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { display: true, color: '#f8fafc' },
                        ticks: { font: { size: 10 }, color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, color: '#94a3b8' }
                    }
                }
            }
        });

        // Revenue Share Chart
        const revenueCtx = document.getElementById('revenueShareChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'doughnut',
            data: {
                labels: ['Services', 'Products'],
                datasets: [{
                    data: [{{ $serviceRevenueShare }}, {{ $productRevenueShare }}],
                    backgroundColor: ['#A855F7', '#F7DF79'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%',
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
@endsection
