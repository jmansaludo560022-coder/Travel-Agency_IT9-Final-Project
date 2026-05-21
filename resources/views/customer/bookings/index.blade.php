@extends('layouts.customer')
@section('title', 'My Bookings')
@section('content')

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">My Bookings</h2>
        <a href="{{ route('packages.index') }}"
            class="flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
            Browse Packages
        </a>
    </div>

    <div class="divide-y divide-gray-100 dark:divide-gray-800">
        @forelse($bookings as $booking)
        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $booking->travelPackage->package_name }}</h3>
                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ $booking->travelPackage->destination->city_name }}, {{ $booking->travelPackage->destination->country }}
                    </p>
                    <div class="mt-2 flex items-center gap-4 text-xs text-gray-500">
                        <span>Booked: {{ $booking->booking_date->format('M d, Y') }}</span>
                        <span>Travel: {{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}</span>
                        <span>Travelers: {{ $booking->travelers->count() }}</span>
                    </div>
                    <div class="mt-2">
                        @php
                            $sc = [
                                'confirmed'  => 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400',
                                'pending'    => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
                                'cancelled'  => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
                                'completed'  => 'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-400',
                                'fully_paid' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-400',
                            ][$booking->booking_status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $sc }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->booking_status)) }}
                        </span>
                    </div>
                </div>
                <div class="text-right ml-6">
                    <p class="text-xl font-bold text-gray-900 dark:text-white">${{ number_format($booking->total_amount, 2) }}</p>
                    <div class="mt-3 flex items-center gap-2 justify-end">
                        <a href="{{ route('customer.bookings.show', $booking->id) }}"
                            class="px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold rounded-lg transition">
                            View Details
                        </a>
                        @if($booking->booking_status === 'pending')
                        <form action="{{ route('customer.bookings.cancel', $booking->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition">
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
            <p class="text-gray-500 dark:text-gray-400 mb-4">You haven't made any bookings yet.</p>
            <a href="{{ route('packages.index') }}"
                class="inline-block px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
                Browse Packages
            </a>
        </div>
        @endforelse
    </div>

    @if($bookings->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection
