@extends('layouts.customer')
@section('title', 'Write a Review')
@section('content')

<div class="max-w-2xl mx-auto bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6">
    <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-6">Write a Review</h2>

    @if($bookings->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400">You have no completed bookings available for review.</p>
            <a href="{{ route('customer.bookings.index') }}"
                class="mt-4 inline-block px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
                View My Bookings
            </a>
        </div>
    @else
        <form action="{{ route('customer.reviews.store') }}" method="POST">
            @csrf
            @php
                $input = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
                $label = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';
            @endphp

            <div class="mb-5">
                <label class="{{ $label }}">Select Booking</label>
                <select name="booking_id" id="booking_id" required class="{{ $input }}">
                    <option value="">Select a completed booking</option>
                    @foreach($bookings as $booking)
                        <option value="{{ $booking->id }}" {{ $bookingId == $booking->id ? 'selected' : '' }}>
                            #{{ $booking->id }} — {{ $booking->travelPackage->package_name }}
                        </option>
                    @endforeach
                </select>
                @error('booking_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="mb-5">
                <label class="{{ $label }}">Rating</label>
                <div class="flex items-center gap-2">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only" {{ old('rating') == $i ? 'checked' : '' }}>
                            <span class="text-3xl text-gray-300 dark:text-gray-600 hover:text-yellow-400 rating-star transition" data-value="{{ $i }}">★</span>
                        </label>
                    @endfor
                </div>
                @error('rating')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="mb-5">
                <label class="{{ $label }}">Your Review</label>
                <textarea name="review_text" id="review_text" rows="5" required
                    class="{{ $input }}"
                    placeholder="Share your experience...">{{ old('review_text') }}</textarea>
                @error('review_text')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                <a href="{{ route('customer.bookings.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="px-5 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
                    Submit Review
                </button>
            </div>
        </form>
    @endif
</div>

<script>
document.querySelectorAll('.rating-star').forEach(function(star) {
    star.addEventListener('click', function() {
        var value = parseInt(this.dataset.value);
        document.querySelectorAll('.rating-star').forEach(function(s, i) {
            s.classList.toggle('text-yellow-400', i < value);
            s.classList.toggle('text-gray-300', i >= value);
            s.classList.toggle('dark:text-gray-600', i >= value);
        });
        this.closest('label').querySelector('input').checked = true;
    });
});
</script>
@endsection
