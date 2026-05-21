@extends('layouts.admin')
@section('title', 'Reviews')
@section('content')
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">All Reviews</h2>
        <p class="text-xs text-gray-500 mt-0.5">Customer reviews for travel packages</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Review</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        {{ $review->customer->cus_fn }} {{ $review->customer->cus_ln }}
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                        {{ Str::limit($review->booking->travelPackage->package_name, 30) }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="text-xs text-gray-500 ml-1">{{ $review->rating }}/5</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ Str::limit($review->review_text, 50) }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $review->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.reviews.show', $review->id) }}" class="text-xs text-sky-600 dark:text-sky-400 hover:underline">View</a>
                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this review?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 dark:hover:text-red-400">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">No reviews found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $reviews->links() }}</div>
</div>
@endsection
