@extends('layouts.agent')
@section('title', 'Bookings')
@section('content')

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">All Bookings</h2>
            <p class="text-xs text-gray-500 mt-0.5">{{ $bookings->total() }} total bookings</p>
        </div>
        <a href="{{ route('agent.bookings.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Create Booking
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Travel Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <td class="px-6 py-4 text-gray-500 text-xs">#{{ $booking->id }}</td>
                    <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $booking->customer->cus_fn }} {{ $booking->customer->cus_ln }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ Str::limit($booking->travelPackage->package_name, 28) }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">${{ number_format($booking->total_amount, 2) }}</td>
                    <td class="px-6 py-4">
                        @php
                            $sc = [
                                'confirmed'  => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20',
                                'pending'    => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/15 dark:text-amber-400 dark:border-amber-500/20',
                                'cancelled'  => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20',
                                'completed'  => 'bg-sky-100 text-sky-700 border-sky-200 dark:bg-sky-500/15 dark:text-sky-400 dark:border-sky-500/20',
                                'fully_paid' => 'bg-indigo-100 text-indigo-700 border-indigo-200 dark:bg-indigo-500/15 dark:text-indigo-400 dark:border-indigo-500/20',
                            ][$booking->booking_status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                        @endphp
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $sc }}">{{ ucfirst(str_replace('_',' ',$booking->booking_status)) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('agent.bookings.show', $booking->id) }}" class="text-xs text-sky-600 dark:text-sky-400 hover:underline">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400 dark:text-gray-600">No bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $bookings->links() }}</div>
</div>
@endsection
