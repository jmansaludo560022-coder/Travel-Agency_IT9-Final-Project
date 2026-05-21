@extends('layouts.customer')
@section('title', 'My Payments')
@section('content')

{{-- Outstanding balances banner --}}
@php
    $unpaidBookings = auth()->user()->customer->bookings()
        ->with('travelPackage')
        ->whereNotIn('booking_status', ['cancelled', 'fully_paid'])
        ->get()
        ->filter(fn($b) => $b->total_amount > $b->payments()->sum('amount_paid'));
@endphp

@if($unpaidBookings->count())
<div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-xl p-4 mb-4">
    <div class="flex items-center gap-2 mb-3">
        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <p class="text-sm font-semibold text-amber-700 dark:text-amber-400">You have {{ $unpaidBookings->count() }} booking(s) with outstanding balance</p>
    </div>
    <div class="space-y-2">
        @foreach($unpaidBookings as $ub)
        @php $bal = $ub->total_amount - $ub->payments()->sum('amount_paid'); @endphp
        <div class="flex items-center justify-between bg-white dark:bg-gray-900 rounded-lg px-4 py-2.5 border border-amber-100 dark:border-amber-500/10">
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $ub->travelPackage->package_name }}</p>
                <p class="text-xs text-gray-500">Booking #{{ $ub->id }} · Balance: <span class="font-semibold text-amber-600 dark:text-amber-400">${{ number_format($bal, 2) }}</span></p>
            </div>
            <a href="{{ route('customer.payments.create', ['booking_id' => $ub->id]) }}"
                class="flex items-center gap-1.5 px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold rounded-lg transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Pay Now
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Payments table --}}
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Payment History</h2>
        <p class="text-xs text-gray-500 mt-0.5">All your submitted payments</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900 dark:text-white">{{ Str::limit($payment->booking->travelPackage->package_name, 28) }}</p>
                        <p class="text-xs text-gray-500">Booking #{{ $payment->booking_id }}</p>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">${{ number_format($payment->amount_paid, 2) }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $payment->payment_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs font-mono">{{ $payment->payment_ref_no }}</td>
                    <td class="px-6 py-4">
                        @php
                            $ps = [
                                'verified' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20',
                                'pending'  => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/15 dark:text-amber-400 dark:border-amber-500/20',
                                'rejected' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20',
                            ][$payment->payment_status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                        @endphp
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $ps }}">
                            {{ ucfirst($payment->payment_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('customer.payments.show', $payment->id) }}"
                            class="text-xs text-sky-600 dark:text-sky-400 hover:underline">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-400">No payments yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($payments->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection
