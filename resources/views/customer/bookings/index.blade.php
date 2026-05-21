@extends('layouts.customer')

@section('title', 'My Bookings')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">My Bookings</h2>
        <a href="{{ route('packages.index') }}" class="px-4 py-2 bg-sky-500 text-white rounded hover:bg-sky-600">
            Browse Packages
        </a>
    </div>

    <div class="divide-y divide-gray-200">
        @forelse($bookings as $booking)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $booking->travelPackage->package_name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $booking->travelPackage->destination->city_name }}, {{ $booking->travelPackage->destination->country }}
                        </p>
                        <div class="mt-3 flex items-center space-x-4 text-sm text-gray-600">
                            <span>Booking Date: {{ $booking->booking_date->format('M d, Y') }}</span>
                            <span>Travel Date: {{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}</span>
                            <span>Travelers: {{ $booking->travelers->count() }}</span>
                        </div>
                        <div class="mt-2">
                            <span class="px-3 py-1 text-xs rounded-full 
                                {{ $booking->booking_status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $booking->booking_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $booking->booking_status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $booking->booking_status === 'completed' ? 'bg-sky-100 text-sky-800' : '' }}
                                {{ $booking->booking_status === 'fully_paid' ? 'bg-sky-100 text-sky-800' : '' }}">
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-2xl font-bold text-gray-800">${{ number_format($booking->total_amount, 2) }}</p>
                        <div class="mt-4 space-x-2">
                            <a href="{{ route('customer.bookings.show', $booking->id) }}" 
                                class="inline-block px-4 py-2 bg-sky-500 text-white text-sm rounded hover:bg-sky-600">
                                View Details
                            </a>
                            @if($booking->booking_status === 'pending')
                                <form action="{{ route('customer.bookings.cancel', $booking->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <p class="text-gray-500 mb-4">You haven't made any bookings yet.</p>
                <a href="{{ route('packages.index') }}" class="inline-block px-6 py-3 bg-sky-500 text-white rounded hover:bg-sky-600">
                    Browse Packages
                </a>
            </div>
        @endforelse
    </div>

    @if($bookings->hasPages())
        <div class="p-6 border-t">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
