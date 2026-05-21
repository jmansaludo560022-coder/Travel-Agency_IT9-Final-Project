@extends('layouts.agent')
@section('title', 'Customer Details')
@section('content')

<div class="max-w-3xl mx-auto space-y-4">

    {{-- Header --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                    {{ $customer->cus_fn }} {{ $customer->cus_mn ? $customer->cus_mn . ' ' : '' }}{{ $customer->cus_ln }}
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Customer profile</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('agent.bookings.create', ['customer_id' => $customer->id]) }}"
                    class="flex items-center gap-1.5 px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Booking
                </a>
                <a href="{{ route('agent.customers.index') }}"
                    class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    Back
                </a>
            </div>
        </div>

        <div class="p-6 grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-xs text-gray-500">Email</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $customer->cus_email }}</p></div>
            <div><p class="text-xs text-gray-500">Phone</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $customer->phone_num }}</p></div>
            <div><p class="text-xs text-gray-500">Username</p><p class="font-mono text-gray-700 dark:text-gray-300 mt-0.5">{{ $customer->userAccount->username ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500">Total Bookings</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $customer->bookings->count() }}</p></div>
        </div>
    </div>

    {{-- Booking History --}}
    @if($customer->bookings->count())
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Booking History</h3>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach($customer->bookings as $booking)
            <div class="px-6 py-3 flex items-center justify-between text-sm">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $booking->travelPackage->package_name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Travel: {{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}
                        · ${{ number_format($booking->total_amount, 2) }}
                    </p>
                </div>
                @php
                    $sc = [
                        'pending'    => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/15 dark:text-amber-400 dark:border-amber-500/20',
                        'confirmed'  => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20',
                        'cancelled'  => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20',
                        'completed'  => 'bg-sky-100 text-sky-700 border-sky-200 dark:bg-sky-500/15 dark:text-sky-400 dark:border-sky-500/20',
                        'fully_paid' => 'bg-indigo-100 text-indigo-700 border-indigo-200 dark:bg-indigo-500/15 dark:text-indigo-400 dark:border-indigo-500/20',
                    ][$booking->booking_status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                @endphp
                <span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $sc }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->booking_status)) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Reviews --}}
    @if($customer->reviews->count())
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Reviews ({{ $customer->reviews->count() }})</h3>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach($customer->reviews as $review)
            <div class="p-6">
                {{-- Rating --}}
                <div class="flex items-center gap-2 mb-2">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</span>
                </div>

                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">{{ $review->review_text }}</p>

                {{-- Existing reply --}}
                @if($review->review_reply)
                <div class="p-3 bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 rounded-lg">
                    <p class="text-xs font-semibold text-sky-600 dark:text-sky-400 mb-1">Your Reply</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $review->review_reply }}</p>
                </div>
                @else
                {{-- Reply form --}}
                <form action="{{ route('agent.reviews.reply', $review->id) }}" method="POST" class="mt-2">
                    @csrf @method('PATCH')
                    <textarea name="review_reply" rows="2" required
                        placeholder="Write a reply to this review..."
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm"></textarea>
                    @error('review_reply')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    <button type="submit"
                        class="mt-2 px-4 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold rounded-lg transition">
                        Submit Reply
                    </button>
                </form>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
