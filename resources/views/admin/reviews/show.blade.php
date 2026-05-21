@extends('layouts.admin')
@section('title', 'Review Details')
@section('content')
<div class="max-w-2xl mx-auto">
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Review Details</h2>
        <a href="{{ route('admin.reviews.index') }}" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">Back</a>
    </div>
    <div class="p-6 space-y-5">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-xs text-gray-500">Customer</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $review->customer->cus_fn }} {{ $review->customer->cus_ln }}</p></div>
            <div><p class="text-xs text-gray-500">Package</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $review->booking->travelPackage->package_name }}</p></div>
            <div><p class="text-xs text-gray-500">Rating</p>
                <div class="flex items-center gap-1 mt-1">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                    <span class="text-sm font-semibold text-gray-900 dark:text-white ml-1">{{ $review->rating }}/5</span>
                </div>
            </div>
            <div><p class="text-xs text-gray-500">Date</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $review->created_at->format('F d, Y') }}</p></div>
        </div>

        <div class="border-t border-gray-100 dark:border-gray-800 pt-4">
            <p class="text-xs text-gray-500 mb-2">Review</p>
            <p class="text-gray-800 dark:text-gray-200 leading-relaxed">{{ $review->review_text }}</p>
        </div>

        @if($review->review_reply)
        <div class="bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 rounded-lg p-4">
            <p class="text-xs font-semibold text-sky-600 dark:text-sky-400 mb-1">Agent Reply</p>
            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $review->review_reply }}</p>
        </div>
        @endif
    </div>
</div>
</div>
@endsection
