@extends('layouts.customer')

@section('title', 'Dashboard')

@section('content')
<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <p class="text-sm font-medium text-gray-500">My Bookings</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($myBookingsCount) }}</p>
        <a href="{{ route('customer.bookings.index') }}" class="mt-2 inline-block text-sm text-sky-600 hover:text-indigo-800">View all →</a>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <p class="text-sm font-medium text-gray-500">Upcoming Trips</p>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($upcomingTrips) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <p class="text-sm font-medium text-gray-500">Pending Payments</p>
        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ number_format($pendingPayments) }}</p>
        @if($pendingPayments > 0)
            <a href="{{ route('customer.payments.index') }}" class="mt-2 inline-block text-sm text-yellow-600 hover:text-yellow-800">View payments →</a>
        @endif
    </div>
</div>

<!-- Recent Bookings -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
        <a href="{{ route('customer.bookings.index') }}" class="text-sm text-sky-600 hover:text-indigo-800">View all</a>
    </div>

    <div class="divide-y divide-gray-200">
        @forelse($recentBookings as $booking)
        <div class="p-6 flex items-center justify-between">
            <div>
                <p class="font-medium text-gray-900">{{ $booking->travelPackage->package_name }}</p>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $booking->travelPackage->destination->city_name }}, {{ $booking->travelPackage->destination->country }}
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    Travel: {{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}
                </p>
            </div>
            <div class="text-right">
                <p class="font-bold text-gray-900">${{ number_format($booking->total_amount, 2) }}</p>
                <span class="mt-1 inline-block px-2 py-1 text-xs rounded-full
                    {{ $booking->booking_status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $booking->booking_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $booking->booking_status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $booking->booking_status === 'fully_paid' ? 'bg-sky-100 text-sky-800' : '' }}
                    {{ $booking->booking_status === 'completed' ? 'bg-sky-100 text-sky-800' : '' }}">
                    {{ ucfirst($booking->booking_status) }}
                </span>
                <div class="mt-2">
                    <a href="{{ route('customer.bookings.show', $booking->id) }}" class="text-sm text-sky-600 hover:text-indigo-800">View →</a>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <p class="text-gray-500 mb-4">No bookings yet. Start exploring packages!</p>
            <a href="{{ route('packages.index') }}" class="inline-block px-6 py-3 bg-sky-500 text-white rounded hover:bg-sky-600">
                Browse Packages
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
