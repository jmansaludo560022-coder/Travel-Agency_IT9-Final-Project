@extends('layouts.customer')

@section('title', 'Edit Review')

@section('content')
<div class="max-w-2xl mx-auto bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Review</h2>

    <form action="{{ route('customer.reviews.update', $review->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="booking_id" value="{{ $review->booking_id }}">

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
            <div class="flex space-x-2">
                @for($i = 1; $i <= 5; $i++)
                    <label class="cursor-pointer">
                        <input type="radio" name="rating" value="{{ $i }}" class="sr-only" {{ old('rating', $review->rating) == $i ? 'checked' : '' }}>
                        <span class="text-3xl rating-star {{ old('rating', $review->rating) >= $i ? 'text-yellow-400' : 'text-gray-300' }}" data-value="{{ $i }}">★</span>
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
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('review_text', $review->review_text) }}</textarea>
            @error('review_text')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end space-x-4 pt-6 border-t">
            <a href="{{ route('customer.bookings.show', $review->booking_id) }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded hover:bg-gray-200">Cancel</a>
            <button type="submit" class="px-6 py-3 bg-sky-500 text-white rounded hover:bg-sky-600">Update Review</button>
        </div>
    </form>
</div>
@endsection
