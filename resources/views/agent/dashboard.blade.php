@extends('layouts.agent')
@section('title', 'Dashboard')
@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6">
        <p class="text-sm font-medium text-gray-500">My Bookings</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($myBookingsCount) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6">
        <p class="text-sm font-medium text-gray-500">Commission This Month</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">${{ number_format($commissionThisMonth['total_commission'], 2) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $commissionThisMonth['bookings_count'] }} confirmed bookings</p>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6">
        <p class="text-sm font-medium text-gray-500">Pending Verifications</p>
        <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-2">{{ number_format($pendingVerifications) }}</p>
    </div>
</div>

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Recent Bookings</h3>
        <a href="{{ route('agent.bookings.index') }}" class="text-sm text-sky-600 dark:text-sky-400 hover:underline">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Travel Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($recentBookings as $booking)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <td class="px-4 py-3 text-gray-900 dark:text-gray-300">{{ $booking->customer->cus_fn }} {{ $booking->customer->cus_ln }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ Str::limit($booking->travelPackage->package_name, 30) }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">${{ number_format($booking->total_amount, 2) }}</td>
                    <td class="px-4 py-3">
                        @php
                            $sc = [
                                'confirmed'  => 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400',
                                'pending'    => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
                                'cancelled'  => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
                                'fully_paid' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-400',
                                'completed'  => 'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-400',
                            ][$booking->booking_status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $sc }}">{{ ucfirst($booking->booking_status) }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400 dark:text-gray-600">No bookings yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
