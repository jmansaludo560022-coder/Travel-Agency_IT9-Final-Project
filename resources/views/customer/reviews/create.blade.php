@extends('layouts.customer')

@section('title', 'Write a Review')

@section('content')
<div class="max-w-2xl mx-auto bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Write a Review</h2>

    @if($bookings->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-500">You have no completed bookings available for review.</p>
            <a href="{{ route('customer.bookings.index') }}" class="mt-4 inline-block px-4 py-2 bg-sky-500 text-white rounded hover:bg-sky-600">
                View My Bookings
            </a>
        </div>
    @else
        <form action="{{ route('customer.reviews.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="booking_id" class="block text-sm font-medium text-gray-700 mb-2">Select Booking</label>
                <select name="booking_id" id="booking_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Select a completed booking</option>
                    @foreach($bookings as $booking)
                        <option value="{{ $booking->id }}" {{ $bookingId == $booking->id ? 'selected' : '' }}>
                            #{{ $booking->id }} - {{ $booking->travelPackage->package_name }}
                        </option>
                    @endforeach
                </select>
                @error('booking_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                <div class="flex space-x-2">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only" {{ old('rating') == $i ? 'checked' : '' }}>
                            <span class="text-3xl text-gray-300 hover:text-yellow-400 rating-star" data-value="{{ $i }}">★</span>
                        </label>
                    @endfor
                </div>
                @error('rating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="review_text" class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                <textarea name="review_text" id="review_text" rows="5" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Share your experience...">{{ old('review_text') }}</textarea>
                @error('review_text')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('customer.bookings.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded hover:bg-gray-200">Cancel</a>
                <button type="submit" class="px-6 py-3 bg-sky-500 text-white rounded hover:bg-sky-600">Submit Review</button>
            </div>
        </form>
    @endif
</div>

<script>
document.querySelectorAll('.rating-star').forEach(star => {
    star.addEventListener('click', function() {
        const value = this.dataset.value;
        document.querySelectorAll('.rating-star').forEach((s, i) => {
            s.classList.toggle('text-yellow-400', i < value);
            s.classList.toggle('text-gray-300', i >= value);
        });
        this.closest('label').querySelector('input').checked = true;
    });
});
</script>
@endsection
