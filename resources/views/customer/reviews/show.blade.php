@extends('layouts.customer')

@section('title', 'My Review')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">My Review</h2>
            <div class="flex gap-2">
                <a href="{{ route('customer.reviews.edit', $review->id) }}"
                   class="px-4 py-2 bg-sky-500 text-white rounded hover:bg-sky-600 text-sm">Edit</a>
                <a href="{{ route('customer.bookings.show', $review->booking_id) }}"
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-sm">Back to Booking</a>
            </div>
        </div>

        <div class="p-6 space-y-5">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Package</p>
                    <p class="text-gray-900 font-medium">{{ $review->booking->travelPackage->package_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Submitted</p>
                    <p class="text-gray-900">{{ $review->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Your Rating</p>
                <div class="flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} text-2xl">★</span>
                    @endfor
                    <span class="ml-2 text-sm text-gray-600">{{ $review->rating }} / 5</span>
                </div>
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Your Review</p>
                <p class="text-gray-800 text-sm leading-relaxed bg-gray-50 rounded p-4">{{ $review->review_text }}</p>
            </div>

            @if($review->review_reply)
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Agent Reply</p>
                <p class="text-gray-800 text-sm leading-relaxed bg-sky-50 rounded p-4 border-l-4 border-sky-400">
                    {{ $review->review_reply }}
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
