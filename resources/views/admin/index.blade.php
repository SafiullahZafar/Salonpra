@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Revenue -->
        <div class="bg-card-yellow p-8 rounded-[2rem] shadow-sm border border-yellow-100 flex items-start space-x-6">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-md">
                <i data-lucide="dollar-sign" class="w-8 h-8 text-yellow-600"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-1">PKR {{ number_format($totalRevenue, 0) }}</h3>
                <div class="flex items-center mt-2 text-green-600 space-x-1">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                    <span class="text-xs font-semibold">+20.5% From Last Day</span>
                </div>
            </div>
        </div>

        <!-- Total Appointments -->
        <div class="bg-card-purple p-8 rounded-[2rem] shadow-sm border border-purple-100 flex items-start space-x-6">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-md">
                <i data-lucide="package" class="w-8 h-8 text-purple-600"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Appointments</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalAppointments, 0) }}</h3>
                <div class="flex items-center mt-2 text-green-600 space-x-1">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                    <span class="text-xs font-semibold">+1.5% From Last Day</span>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-card-cyan p-8 rounded-[2rem] shadow-sm border border-cyan-100 flex items-start space-x-6">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-md">
                <i data-lucide="users" class="w-8 h-8 text-cyan-600"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Customers</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalCustomers, 0) }}</h3>
                <div class="flex items-center mt-2 text-green-600 space-x-1">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                    <span class="text-xs font-semibold">+20.5% From Last Day</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Appointments Overview -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h4 class="text-lg font-bold text-gray-800">Appointments Overview</h4>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-primary-gold"></span>
                        <span class="text-xs text-gray-500">Appointments</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                        <span class="text-xs text-gray-500">Profit</span>
                    </div>
                    <select class="text-xs bg-gray-50 border-none rounded-lg px-2 py-1 shadow-sm">
                        <option>2024</option>
                    </select>
                </div>
            </div>
            <div class="h-64">
                <canvas id="appointmentsChart"></canvas>
            </div>
        </div>

        <!-- Sale Analytics & Top Services -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Sale Analytics (Donut) -->
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 mb-6">Sale Analytics</h4>
                <div class="relative h-48 flex items-center justify-center">
                    <canvas id="salesAnalyticsChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-bold text-gray-800">100%</span>
                        <span class="text-[10px] text-gray-400 font-medium">Completed</span>
                    </div>
                </div>
                <div class="mt-8 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="w-2 h-2 rounded-full bg-primary-gold"></span>
                            <span class="text-xs text-gray-600 font-medium">Completed</span>
                        </div>
                        <span class="text-xs font-bold text-gray-800">20%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                            <span class="text-xs text-gray-600 font-medium">Distributed</span>
                        </div>
                        <span class="text-xs font-bold text-gray-800">10%</span>
                    </div>
                </div>
            </div>

            <!-- Top Services -->
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <h4 class="text-lg font-bold text-gray-800">Top Services</h4>
                    <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Appointments</span>
                </div>
                <div class="space-y-6">
                    @php
                        $topServices = [
                            ['name' => 'Realistic', 'code' => '8812', 'count' => '432'],
                            ['name' => 'Monst...', 'code' => '8832', 'count' => '324'],
                            ['name' => 'Product', 'code' => '9871', 'count' => '1,122'],
                            ['name' => 'Product', 'code' => '2211', 'count' => '9,876'],
                        ];
                    @endphp
                    @foreach($topServices as $service)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center">
                                <i data-lucide="box" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $service['name'] }}</p>
                                <p class="text-[10px] text-gray-400 font-medium">{{ $service['code'] }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-gray-800">{{ $service['count'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Appointments Overview Chart
        const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
        new Chart(appointmentsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Appointments',
                    data: [30, 45, 40, 50, 48, 70, 65, 78, 68, 70, 60, 80],
                    borderColor: '#F7DF79',
                    borderWidth: 3,
                    tension: 0.4,
                    pointRadius: 0
                }, {
                    label: 'Profit',
                    data: [25, 40, 35, 45, 42, 55, 52, 60, 55, 50, 52, 65],
                    borderColor: '#A855F7',
                    borderWidth: 3,
                    tension: 0.4,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: true, color: '#f3f4f6' },
                        border: { display: false },
                        ticks: {
                            callback: value => value + 'K',
                            color: '#9ca3af',
                            font: { size: 10 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#9ca3af', font: { size: 10 } }
                    }
                }
            }
        });

        // Sales Analytics Chart
        const salesCtx = document.getElementById('salesAnalyticsChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [70, 30],
                    backgroundColor: ['#A855F7', '#F7DF79'],
                    borderWidth: 0,
                    cutout: '85%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
@endsection
