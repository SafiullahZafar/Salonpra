@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Staff Management</h2>
            <p class="text-gray-500 mt-1">Manage your team, their roles, and specialized skills.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('staff.performance-dashboard') }}" class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 text-sm font-medium text-gray-600 hover:bg-gray-50 flex items-center space-x-2">
                <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                <span>Performance</span>
            </a>
            <button class="bg-primary-gold px-6 py-2 rounded-xl shadow-md text-sm font-bold text-black hover:bg-yellow-400 transition-all flex items-center space-x-2">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>Hire Staff</span>
            </button>
        </div>
    </div>

    <!-- Staff Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($staff as $member)
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 flex flex-col items-center text-center relative group hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-4 right-4">
                <span class="w-3 h-3 rounded-full {{ $member->status ? 'bg-green-500' : 'bg-gray-300' }} shadow-[0_0_8px_rgba(34,197,94,0.4)]"></span>
            </div>
            
            <div class="relative mb-6">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&size=100&background=F7DF79&color=000" class="w-24 h-24 rounded-3xl shadow-lg border-4 border-white transform group-hover:scale-110 transition-transform duration-300" alt="">
                <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-sidebar-dark rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="scissors" class="w-4 h-4"></i>
                </div>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800">{{ $member->name }}</h3>
            <p class="text-xs font-bold text-primary-gold uppercase tracking-widest mt-1">{{ $member->position }}</p>
            
            <div class="mt-6 w-full flex items-center justify-around py-4 border-y border-gray-50">
                <div class="text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Rate</p>
                    <p class="text-sm font-black text-gray-800">PKR {{ number_format($member->hourly_rate, 0) }}</p>
                </div>
                <div class="w-px h-8 bg-gray-100"></div>
                <div class="text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Upsell</p>
                    <p class="text-sm font-black text-purple-600">PKR {{ number_format($member->upsellPerformance?->upsell_revenue ?? 0, 0) }}</p>
                </div>
            </div>
            
            <div class="mt-6 flex items-center space-x-3 w-full">
                <a href="{{ route('staff.show', $member) }}" class="flex-1 py-3 bg-gray-50 text-gray-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary-gold hover:text-black transition-all">
                    Profile
                </a>
                <button class="p-3 bg-gray-50 text-gray-400 rounded-2xl hover:bg-blue-50 transition-all hover:text-blue-500">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $staff->links() }}
    </div>
</div>
@endsection
