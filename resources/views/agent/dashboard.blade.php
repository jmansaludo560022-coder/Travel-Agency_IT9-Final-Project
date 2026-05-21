@extends('layouts.agent')

@section('title', 'Dashboard')

@section('content')
<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <p class="text-sm font-medium text-gray-500">My Bookings</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($myBookingsCount) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <p class="text-sm font-medium text-gray-500">Commission This Month</p>
        <p class="text-3xl font-bold text-green-600 mt-2">${{ number_format($commissionThisMonth['total_commission'], 2) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $commissionThisMonth['bookings_count'] }} confirmed bookings</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <p class="text-sm font-medium text-gray-500">Pending Verifications</p>
        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ number_format($pendingVerifications) }}</p>
    </div>
</div>

<!-- Recent Bookings -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
        <a href="{{ route('agent.bookings.index') }}" class="text-sm text-sky-600 hover:text-indigo-800">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Travel Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recentBookings as $booking)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $booking->customer->cus_fn }} {{ $booking->customer->cus_ln }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ Str::limit($booking->travelPackage->package_name, 30) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-sm font-semibold">${{ number_format($booking->total_amount, 2) }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $booking->booking_status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->booking_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->booking_status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $booking->booking_status === 'fully_paid' ? 'bg-sky-100 text-sky-800' : '' }}">
                            {{ ucfirst($booking->booking_status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">No bookings yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
