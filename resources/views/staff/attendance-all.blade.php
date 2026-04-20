@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Staff Attendance & Payroll</h2>
            <p class="text-gray-500 mt-1">Monitor daily presence and calculate work hours for payroll processing.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 text-sm font-medium text-gray-600 hover:bg-gray-50 flex items-center space-x-2">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Print Report</span>
            </button>
            <button class="bg-primary-gold px-6 py-2 rounded-xl shadow-md text-sm font-bold text-black hover:bg-yellow-400 transition-all flex items-center space-x-2">
                <i data-lucide="check-square" class="w-4 h-4"></i>
                <span>Mark Attendance</span>
            </button>
        </div>
    </div>

    <!-- Attendance Log Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <th class="px-8 py-5">Staff Member</th>
                        <th class="px-8 py-5">Date</th>
                        <th class="px-8 py-5">Shift Timing</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5">Total Hours</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($records as $record)
                    <tr class="group hover:bg-gray-50 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($record->staff->name) }}&background=F3F4F6&color=6B7280" class="w-10 h-10 rounded-xl" alt="">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $record->staff->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium">{{ $record->staff->position }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xs font-bold text-gray-600">{{ \Carbon\Carbon::parse($record->attendance_date)->format('d M Y') }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                @if($record->check_in_time)
                                    <span class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::parse($record->check_in_time)->format('h:i A') }} - {{ $record->check_out_time ? \Carbon\Carbon::parse($record->check_out_time)->format('h:i A') : '--:--' }}</span>
                                @else
                                    <span class="text-xs text-gray-300 italic">No time logs</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @php
                                $statusStyles = [
                                    'present' => 'bg-green-50 text-green-600 border-green-100',
                                    'absent' => 'bg-red-50 text-red-600 border-red-100',
                                    'late' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                    'half_day' => 'bg-orange-50 text-orange-600 border-orange-100',
                                    'leave' => 'bg-blue-50 text-blue-600 border-blue-100',
                                ];
                                $style = $statusStyles[$record->status] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                            @endphp
                            <span class="px-3 py-1 {{ $style }} rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm">
                                {{ $record->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            @if($record->check_in_time && $record->check_out_time)
                                @php
                                    $in = \Carbon\Carbon::parse($record->check_in_time);
                                    $out = \Carbon\Carbon::parse($record->check_out_time);
                                    $hours = $out->diffInHours($in);
                                @endphp
                                <span class="text-sm font-black text-gray-800">{{ $hours }}h</span>
                            @else
                                <span class="text-sm font-black text-gray-300">--</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <button class="p-2 text-gray-300 hover:text-primary-gold transition-all">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
            {{ $records->links() }}
        </div>
    </div>

    <!-- Quick Insights -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-card-yellow p-8 rounded-[3rem] shadow-sm border border-yellow-100 group">
            <h4 class="text-lg font-bold text-gray-800 mb-6">Today's Summary</h4>
            <div class="grid grid-cols-3 gap-6">
                <div>
                    <p class="text-[10px] font-extrabold text-yellow-700 uppercase tracking-widest">Present</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">12</p>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold text-yellow-700 uppercase tracking-widest">Late</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">2</p>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold text-yellow-700 uppercase tracking-widest">Absent</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">1</p>
                </div>
            </div>
        </div>

        <div class="bg-card-purple p-8 rounded-[3rem] shadow-sm border border-purple-100 group">
            <h4 class="text-lg font-bold text-gray-800 mb-6">Payroll Estimate (MTD)</h4>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-purple-600 uppercase">Estimated Total</p>
                    <h3 class="text-3xl font-black text-gray-900 mt-1">PKR 4,280.00</h3>
                </div>
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-md">
                    <i data-lucide="banknote" class="w-8 h-8 text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
