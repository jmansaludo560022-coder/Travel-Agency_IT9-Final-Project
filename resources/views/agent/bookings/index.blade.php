@extends('layouts.agent')

@section('title', 'Bookings')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">All Bookings</h2>
        <a href="{{ route('agent.bookings.create') }}" class="px-4 py-2 bg-sky-500 text-white rounded hover:bg-sky-600">
            Create Booking
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Travel Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $booking)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $booking->customer->cus_fn }} {{ $booking->customer->cus_ln }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->travelPackage->package_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($booking->total_amount, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $booking->booking_status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->booking_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->booking_status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $booking->booking_status === 'completed' ? 'bg-sky-100 text-sky-800' : '' }}
                            {{ $booking->booking_status === 'fully_paid' ? 'bg-sky-100 text-sky-800' : '' }}">
                            {{ ucfirst($booking->booking_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('agent.bookings.show', $booking->id) }}" class="text-sky-600 hover:text-sky-900">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No bookings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-6 border-t">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
