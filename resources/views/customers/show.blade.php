@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header with Breadcrumbs & Actions -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('customers.index') }}" class="p-3 bg-white rounded-2xl shadow-sm border border-gray-100 text-gray-400 hover:text-black hover:shadow-md transition-all">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </a>
            <div>
                <nav class="flex text-[10px] font-extrabold uppercase tracking-widest text-gray-400 mb-1">
                    <a href="{{ route('customers.index') }}" class="hover:text-primary-gold transition-colors">Customers</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-800">Profile Details</span>
                </nav>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">{{ $customer->name }}</h2>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <button class="bg-white px-6 py-2.5 rounded-2xl shadow-sm border border-gray-100 text-sm font-bold text-gray-600 hover:bg-gray-50 flex items-center space-x-2 transition-all">
                <i data-lucide="edit-3" class="w-4 h-4"></i>
                <span>Edit Profile</span>
            </button>
            <a href="{{ route('whatsapp.send', $customer) }}" target="_blank" class="bg-[#25D366] px-6 py-2.5 rounded-2xl shadow-md text-sm font-bold text-white hover:opacity-90 flex items-center space-x-2 transition-all">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <span>Message</span>
            </a>
            <button class="bg-primary-gold px-8 py-2.5 rounded-2xl shadow-lg text-sm font-bold text-black hover:bg-yellow-400 flex items-center space-x-2 transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Book Service</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
        <!-- Left Column: Personal Info & Membership -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Profile Card -->
            <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary-gold opacity-5 rounded-bl-[5rem]"></div>
                
                <div class="relative inline-block mb-6">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&size=120&background=F3F4F6&color=6B7280" class="w-32 h-32 rounded-[2.5rem] shadow-xl border-4 border-white" alt="">
                    @if($customer->membership_type)
                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-primary-gold rounded-2xl flex items-center justify-center border-4 border-white shadow-md">
                        <i data-lucide="crown" class="w-5 h-5 text-black"></i>
                    </div>
                    @endif
                </div>
                
                <h3 class="text-2xl font-black text-gray-800 mb-2">{{ $customer->name }}</h3>
                <div class="inline-flex items-center px-4 py-1.5 bg-gray-50 rounded-full text-[10px] font-extrabold uppercase tracking-widest text-gray-500 mb-8 border border-gray-100">
                    ID: #{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-50 p-4 rounded-3xl text-center hover:bg-white hover:shadow-md transition-all border border-transparent hover:border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Prepaid</p>
                        <p class="text-xl font-black text-green-600">${{ number_format($customer->prepaid_credit, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-3xl text-center hover:bg-white hover:shadow-md transition-all border border-transparent hover:border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Visits</p>
                        <p class="text-xl font-black text-purple-600">{{ $invoices->count() }}</p>
                    </div>
                </div>

                <div class="space-y-4 text-left">
                    <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 group">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-gray-400 group-hover:text-primary-gold group-hover:shadow-md transition-all">
                            <i data-lucide="phone" class="w-5 h-5"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Phone Number</p>
                            <p class="text-sm font-bold text-gray-800">{{ $customer->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 group">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-gray-400 group-hover:text-blue-500 group-hover:shadow-md transition-all">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email Address</p>
                            <p class="text-sm font-bold text-gray-800 lowercase break-all">{{ $customer->email ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 group">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-gray-400 group-hover:text-pink-500 group-hover:shadow-md transition-all">
                            <i data-lucide="cake" class="w-5 h-5"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Birthday</p>
                            <p class="text-sm font-bold text-gray-800">{{ $customer->birthday ? \Carbon\Carbon::parse($customer->birthday)->format('d M, Y') : 'Not set' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-bold text-gray-800">Internal Notes</h4>
                    <i data-lucide="sticky-note" class="w-5 h-5 text-yellow-400"></i>
                </div>
                <div class="p-6 bg-yellow-50 rounded-3xl border border-yellow-100 relative">
                    <div class="absolute top-0 right-0 w-12 h-12 bg-yellow-100 opacity-50 rounded-bl-3xl"></div>
                    <p class="text-sm text-yellow-800 leading-relaxed italic">
                        {{ $customer->notes ?? 'No special notes recorded for this customer.' }}
                    </p>
                </div>
                <button class="w-full mt-6 py-3 px-4 bg-white border border-gray-200 rounded-2xl text-[10px] font-extrabold uppercase tracking-widest text-gray-400 hover:border-primary-gold hover:text-primary-gold hover:shadow-sm transition-all">
                    Update Notes
                </button>
            </div>
        </div>

        <!-- Right Column: History & Stats -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Appointment History -->
            <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h4 class="text-2xl font-black text-gray-800">Visit History</h4>
                        <p class="text-xs text-gray-400 font-medium">Tracking recent appointments and services</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="p-2 bg-gray-50 rounded-xl text-gray-400">
                            <i data-lucide="history" class="w-5 h-5"></i>
                        </span>
                    </div>
                </div>

                <div class="space-y-6">
                    @forelse($appointments as $appointment)
                    <div class="flex items-center justify-between p-6 bg-gray-50 rounded-[2rem] hover:bg-white hover:shadow-xl hover:border-gray-50 border border-transparent transition-all duration-300 group">
                        <div class="flex items-center space-x-6">
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm text-gray-300 group-hover:text-primary-gold transition-colors">
                                <i data-lucide="calendar" class="w-8 h-8"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">{{ \Carbon\Carbon::parse($appointment->start_time)->format('D, d M Y') }}</p>
                                <p class="text-lg font-bold text-gray-800">{{ $appointment->service->name ?? 'Service' }}</p>
                                <div class="flex items-center mt-1 space-x-3">
                                    <span class="flex items-center text-xs text-gray-400">
                                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                                    </span>
                                    <span class="flex items-center text-xs text-gray-400">
                                        <i data-lucide="user" class="w-3 h-3 mr-1"></i>
                                        {{ $appointment->user->name ?? 'Staff' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-4 py-1.5 bg-white text-gray-400 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm group-hover:bg-primary-gold group-hover:text-black transition-all">
                                {{ $appointment->status }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-16 px-8 bg-gray-50 rounded-[3rem] border border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                            <i data-lucide="calendar-off" class="w-8 h-8 text-gray-200"></i>
                        </div>
                        <p class="text-gray-400 font-bold">No appointment history found</p>
                        <button class="mt-4 text-[10px] font-extrabold text-primary-gold uppercase tracking-widest hover:underline">Book first appointment</button>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Invoices -->
            <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
                <h4 class="text-2xl font-black text-gray-800 mb-8">Recent Payments</h4>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-4">
                                <th class="pb-4">Invoice #</th>
                                <th class="pb-4">Date</th>
                                <th class="pb-4">Method</th>
                                <th class="pb-4">Total</th>
                                <th class="pb-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($invoices as $invoice)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-5">
                                    <span class="text-sm font-bold text-gray-800">#{{ $invoice->invoice_no }}</span>
                                </td>
                                <td class="py-5">
                                    <span class="text-sm font-medium text-gray-500">{{ $invoice->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="py-5">
                                    <span class="px-3 py-1 bg-gray-100 rounded-full text-[10px] font-bold text-gray-600 uppercase">{{ $invoice->payment_method }}</span>
                                </td>
                                <td class="py-5">
                                    <span class="text-sm font-black text-gray-900">${{ number_format($invoice->payable_amount, 2) }}</span>
                                </td>
                                <td class="py-5 text-right">
                                    <button class="p-2 text-gray-300 hover:text-primary-gold transition-colors">
                                        <i data-lucide="download" class="w-5 h-5"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
