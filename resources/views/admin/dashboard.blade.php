@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['Total Bookings',   $totalBookings,                    'text-sky-600 dark:text-sky-400',    'bg-sky-50 dark:bg-sky-500/10 border-sky-200 dark:border-sky-500/20',    'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['Total Revenue',    '$'.number_format($totalRevenue,2), 'text-green-600 dark:text-green-400',  'bg-green-50 dark:bg-green-500/10 border-green-200 dark:border-green-500/20','M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 6v1m0 4v1m-3-1h6M9 7H6'],
        ['Active Packages',  $activePackages,                   'text-indigo-600 dark:text-indigo-400', 'bg-indigo-50 dark:bg-indigo-500/10 border-indigo-200 dark:border-indigo-500/20','M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
        ['Pending Payments', $pendingPayments,                  'text-amber-600 dark:text-amber-400',  'bg-amber-50 dark:bg-amber-500/10 border-amber-200 dark:border-amber-500/20', 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
    ] as [$label, $value, $color, $bg, $icon])
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl {{ $bg }} border flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 {{ $color }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">{{ $label }}</p>
            <p class="text-2xl font-bold {{ $color }} mt-0.5">{{ $value }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Recent Bookings + Commission --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

    {{-- Recent Bookings --}}
    <div class="xl:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Recent Bookings</h3>
            <a href="{{ route('admin.bookings.index') }}" class="text-xs text-sky-600 dark:text-sky-400 hover:text-sky-500 dark:hover:text-sky-300 transition">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($recentBookings as $booking)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $booking->customer->cus_fn }} {{ $booking->customer->cus_ln }}</td>
                        <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">{{ Str::limit($booking->travelPackage->package_name, 28) }}</td>
                        <td class="px-5 py-3.5 font-semibold text-gray-900 dark:text-white">${{ number_format($booking->total_amount, 2) }}</td>
                        <td class="px-5 py-3.5">
                            @php
                                $statusColors = [
                                    'pending'    => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/15 dark:text-amber-400 dark:border-amber-500/20',
                                    'confirmed'  => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20',
                                    'cancelled'  => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20',
                                    'completed'  => 'bg-sky-100 text-sky-700 border-sky-200 dark:bg-sky-500/15 dark:text-sky-400 dark:border-sky-500/20',
                                    'fully_paid' => 'bg-indigo-100 text-indigo-700 border-indigo-200 dark:bg-indigo-500/15 dark:text-indigo-400 dark:border-indigo-500/20',
                                ];
                                $sc = $statusColors[$booking->booking_status] ?? 'bg-gray-100 text-gray-600 border-gray-200 dark:bg-gray-500/15 dark:text-gray-400 dark:border-gray-500/20';
                            @endphp
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $sc }}">
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-400 dark:text-gray-600 text-sm">No bookings yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Commission Summary --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Commission This Month</h3>
            <a href="{{ route('admin.commissions.report') }}" class="text-xs text-sky-600 dark:text-sky-400 hover:text-sky-500 dark:hover:text-sky-300 transition">Report →</a>
        </div>
        <div class="p-5">
            <p class="text-3xl font-extrabold text-green-600 dark:text-green-400 mb-1">${{ number_format($totalCommission, 2) }}</p>
            <p class="text-xs text-gray-500 mb-5">Total earned this month</p>
            <div class="space-y-3">
                @forelse($commissionSummary as $row)
                    @if($row['bookings_count'] > 0)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-sky-100 dark:bg-sky-500/20 border border-sky-200 dark:border-sky-500/30 flex items-center justify-center">
                                <span class="text-sky-600 dark:text-sky-400 text-xs font-bold">{{ strtoupper(substr($row['employee_name'], 0, 1)) }}</span>
                            </div>
                            <span class="text-sm text-gray-700 dark:text-gray-300 truncate max-w-28">{{ $row['employee_name'] }}</span>
                        </div>
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">${{ number_format($row['total_commission'], 2) }}</span>
                    </div>
                    @endif
                @empty
                    <p class="text-sm text-gray-400 dark:text-gray-600 text-center py-4">No commission data this month.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
