@extends('layouts.customer')
@section('title', 'Dashboard')
@section('content')

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6">
        <p class="text-sm font-medium text-gray-500">My Bookings</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($myBookingsCount) }}</p>
        <a href="{{ route('customer.bookings.index') }}" class="mt-2 inline-block text-sm text-sky-600 dark:text-sky-400 hover:underline">View all →</a>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6">
        <p class="text-sm font-medium text-gray-500">Upcoming Trips</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ number_format($upcomingTrips) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6">
        <p class="text-sm font-medium text-gray-500">Pending Payments</p>
        <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-2">{{ number_format($pendingPayments) }}</p>
        @if($pendingPayments > 0)
            <a href="{{ route('customer.payments.index') }}" class="mt-2 inline-block text-sm text-amber-600 dark:text-amber-400 hover:underline">View payments →</a>
        @endif
    </div>
</div>

<!-- Recent Bookings -->
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Recent Bookings</h3>
        <a href="{{ route('customer.bookings.index') }}" class="text-sm text-sky-600 dark:text-sky-400 hover:underline">View all</a>
    </div>

    <div class="divide-y divide-gray-100 dark:divide-gray-800">
        @forelse($recentBookings as $booking)
        <div class="p-6 flex items-center justify-between">
            <div>
                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->travelPackage->package_name }}</p>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $booking->travelPackage->destination->city_name }}, {{ $booking->travelPackage->destination->country }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Travel: {{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-gray-900 dark:text-white">${{ number_format($booking->total_amount, 2) }}</p>
                @php
                    $sc = [
                        'confirmed'  => 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400',
                        'pending'    => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
                        'cancelled'  => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
                        'fully_paid' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-400',
                        'completed'  => 'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-400',
                    ][$booking->booking_status] ?? 'bg-gray-100 text-gray-600';
                @endphp
                <span class="mt-1 inline-block px-2.5 py-0.5 text-xs font-medium rounded-full {{ $sc }}">
                    {{ ucfirst($booking->booking_status) }}
                </span>
                <div class="mt-2">
                    <a href="{{ route('customer.bookings.show', $booking->id) }}" class="text-sm text-sky-600 dark:text-sky-400 hover:underline">View →</a>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <p class="text-gray-500 dark:text-gray-400 mb-4">No bookings yet. Start exploring packages!</p>
            <a href="{{ route('packages.index') }}" class="inline-block px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white rounded-lg text-sm font-semibold transition">
                Browse Packages
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
