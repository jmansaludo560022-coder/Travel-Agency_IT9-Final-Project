@extends('layouts.customer')
@section('title', 'My Review')
@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">My Review</h2>
            <div class="flex gap-2">
                <a href="{{ route('customer.reviews.edit', $review->id) }}"
                    class="px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold rounded-lg transition">Edit</a>
                <a href="{{ route('customer.bookings.show', $review->booking_id) }}"
                    class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    Back to Booking
                </a>
            </div>
        </div>

        <div class="p-6 space-y-5">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Package</p>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $review->booking->travelPackage->package_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Submitted</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $review->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-2">Your Rating</p>
                <div class="flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }} text-2xl">★</span>
                    @endfor
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $review->rating }} / 5</span>
                </div>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-2">Your Review</p>
                <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                    {{ $review->review_text }}
                </p>
            </div>

            @if($review->review_reply)
            <div>
                <p class="text-xs text-gray-500 mb-2">Agent Reply</p>
                <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed bg-sky-50 dark:bg-sky-500/10 rounded-lg p-4 border-l-4 border-sky-400 dark:border-sky-500">
                    {{ $review->review_reply }}
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
