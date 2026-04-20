@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Customer Relationships</h2>
            <p class="text-gray-500 mt-1">Manage your client base and their loyalty profiles.</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative group">
                <input type="text" placeholder="Search customers..." class="bg-white border-gray-200 rounded-xl py-2 px-10 w-64 shadow-sm focus:ring-2 focus:ring-primary-gold transition-all duration-300">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            </div>
            <button class="bg-primary-gold px-6 py-2 rounded-xl shadow-md text-sm font-bold text-black hover:bg-yellow-400 transition-all flex items-center space-x-2">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>Add Customer</span>
            </button>
        </div>
    </div>

    <!-- Customer Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <th class="px-8 py-5">Customer Profile</th>
                        <th class="px-8 py-5">Contact Info</th>
                        <th class="px-8 py-5">Membership</th>
                        <th class="px-8 py-5">Balance</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($customers as $customer)
                    <tr class="group hover:bg-gray-50 transition-all duration-200">
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=F3F4F6&color=6B7280" class="w-12 h-12 rounded-2xl shadow-sm" alt="">
                                    @if($customer->membership_type)
                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-primary-gold rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                                        <i data-lucide="star" class="w-2.5 h-2.5 text-black fill-current"></i>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $customer->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium italic">Joined {{ $customer->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="space-y-1">
                                <p class="text-xs font-semibold text-gray-600 flex items-center space-x-2">
                                    <i data-lucide="phone" class="w-3 h-3 text-gray-400"></i>
                                    <span>{{ $customer->phone ?? 'No phone' }}</span>
                                </p>
                                <p class="text-xs text-gray-400 flex items-center space-x-2">
                                    <i data-lucide="mail" class="w-3 h-3 text-gray-300"></i>
                                    <span>{{ $customer->email ?? 'N/A' }}</span>
                                </p>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($customer->membership_type)
                                <span class="px-3 py-1 bg-yellow-50 text-yellow-700 rounded-full text-[10px] font-bold uppercase tracking-tight shadow-sm border border-yellow-100">
                                    {{ $customer->membership_type }}
                                </span>
                            @else
                                <span class="text-[10px] text-gray-300 font-bold uppercase tracking-tight italic">Standard</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold {{ $customer->prepaid_credit > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                    ${{ number_format($customer->prepaid_credit, 2) }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-medium">Prepaid Balance</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('customers.show', $customer) }}" class="p-2 bg-gray-50 text-gray-400 hover:bg-primary-gold hover:text-black rounded-xl transition-all shadow-sm">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <button class="p-2 bg-gray-50 text-gray-400 hover:bg-blue-500 hover:text-white rounded-xl transition-all shadow-sm">
                                    <i data-lucide="message-square" class="w-4 h-4"></i>
                                </button>
                                <button class="p-2 bg-gray-50 text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition-all shadow-sm">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} Clients
            </p>
            <div>
                {{ $customers->links() }}
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-card-purple p-8 rounded-[2.5rem] shadow-sm border border-purple-100 flex items-center space-x-6 group">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                <i data-lucide="cake" class="w-8 h-8 text-purple-600"></i>
            </div>
            <div>
                <h4 class="text-lg font-bold text-gray-800">Birthdays Today</h4>
                <p class="text-3xl font-black text-purple-900 mt-1">2</p>
                <button class="text-[10px] font-extrabold text-purple-600 uppercase tracking-widest mt-2 hover:underline">Send Greetings</button>
            </div>
        </div>

        <div class="bg-card-cyan p-8 rounded-[2.5rem] shadow-sm border border-cyan-100 flex items-center space-x-6 group">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                <i data-lucide="user-check" class="w-8 h-8 text-cyan-600"></i>
            </div>
            <div>
                <h4 class="text-lg font-bold text-gray-800">New This Week</h4>
                <p class="text-3xl font-black text-cyan-900 mt-1">14</p>
                <p class="text-[10px] text-cyan-600 uppercase tracking-widest font-bold mt-2">+12% from last week</p>
            </div>
        </div>

        <div class="bg-card-yellow p-8 rounded-[2.5rem] shadow-sm border border-yellow-100 flex items-center space-x-6 group">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                <i data-lucide="medal" class="w-8 h-8 text-yellow-600"></i>
            </div>
            <div>
                <h4 class="text-lg font-bold text-gray-800">VIP Members</h4>
                <p class="text-3xl font-black text-yellow-900 mt-1">45</p>
                <p class="text-[10px] text-yellow-600 uppercase tracking-widest font-bold mt-2">Active loyalty members</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom pagination styles to match the theme */
    .pagination {
        display: flex;
        list-style: none;
        gap: 0.5rem;
    }
    .page-item .page-link {
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        background: white;
        color: #6B7280;
        font-weight: bold;
        font-size: 0.75rem;
        border: 1px solid #F3F4F6;
        transition: all 0.2s;
    }
    .page-item.active .page-link {
        background: #F7DF79;
        color: black;
        border-color: #F7DF79;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }
    .page-item .page-link:hover:not(.active) {
        background: #F9FAFB;
        color: #111827;
    }
</style>
@endsection
