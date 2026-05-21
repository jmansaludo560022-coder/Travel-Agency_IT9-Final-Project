@extends('layouts.admin')
@section('title', 'Booking Details')
@section('content')
@php
    $amountPaid = $booking->payments->sum('amount_paid');
    $balance    = $booking->total_amount - $amountPaid;
    $paidPct    = $booking->total_amount > 0 ? min(100, ($amountPaid / $booking->total_amount) * 100) : 0;
    $hasPayment = $booking->payments->count() > 0;
    $sc = [
        'pending'    => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
        'confirmed'  => 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400',
        'cancelled'  => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
        'completed'  => 'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-400',
        'fully_paid' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-400',
    ][$booking->booking_status] ?? 'bg-gray-100 text-gray-600';
@endphp

<div class="max-w-3xl mx-auto space-y-4">

    {{-- ── Header ─────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Booking #{{ $booking->id }}</h2>
                <span class="mt-1 inline-block px-2.5 py-0.5 text-xs font-medium rounded-full {{ $sc }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->booking_status)) }}
                </span>
            </div>
            <div class="flex items-center gap-2">
                {{-- Confirm / Cancel for pending --}}
                @if($booking->booking_status === 'pending')
                    @if(!$hasPayment)
                    <div class="flex items-center gap-1.5 px-3 py-2 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-lg text-xs text-amber-700 dark:text-amber-400">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        Payment required to confirm
                    </div>
                    @endif
                    <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit"
                            class="flex items-center gap-1.5 px-4 py-2 text-white text-sm font-semibold rounded-lg transition
                                {{ $hasPayment ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-300 dark:bg-gray-700 cursor-not-allowed' }}"
                            {{ !$hasPayment ? 'disabled' : '' }}
                            onclick="return confirm('Confirm this booking?')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Confirm
                        </button>
                    </form>
                    <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit"
                            class="flex items-center gap-1.5 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition"
                            onclick="return confirm('Cancel this booking?')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.bookings.index') }}"
                    class="px-3 py-2 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    Back
                </a>
            </div>
        </div>

        {{-- Booking details --}}
        <div class="p-6 grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-xs text-gray-500">Customer</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $booking->customer->cus_fn }} {{ $booking->customer->cus_ln }}</p></div>
            <div><p class="text-xs text-gray-500">Package</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $booking->travelPackage->package_name }}</p></div>
            <div><p class="text-xs text-gray-500">Destination</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $booking->travelPackage->destination->city_name }}, {{ $booking->travelPackage->destination->country }}</p></div>
            <div><p class="text-xs text-gray-500">Travel Date</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ \Carbon\Carbon::parse($booking->travel_date)->format('F d, Y') }}</p></div>
            <div><p class="text-xs text-gray-500">Booking Date</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $booking->booking_date->format('F d, Y') }}</p></div>
            @if($booking->employee)
            <div><p class="text-xs text-gray-500">Handled By</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $booking->employee->emp_fn }} {{ $booking->employee->emp_ln }}</p></div>
            @endif
        </div>
    </div>

    {{-- ── Payment Summary ─────────────────────────────────── --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Payment Summary</h3>
            @if($booking->booking_status !== 'cancelled' && $balance > 0)
            <a href="{{ route('admin.payments.create', ['booking_id' => $booking->id]) }}"
                class="flex items-center gap-1.5 px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold rounded-lg transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Record Payment
            </a>
            @endif
        </div>
        <div class="p-5">
            <div class="grid grid-cols-3 gap-3 text-center mb-4">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                    <p class="text-xs text-gray-500 mb-0.5">Total</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">${{ number_format($booking->total_amount, 2) }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-500/10 rounded-lg p-3">
                    <p class="text-xs text-gray-500 mb-0.5">Paid</p>
                    <p class="text-sm font-bold text-green-600 dark:text-green-400">${{ number_format($amountPaid, 2) }}</p>
                </div>
                <div class="bg-amber-50 dark:bg-amber-500/10 rounded-lg p-3">
                    <p class="text-xs text-gray-500 mb-0.5">Balance</p>
                    <p class="text-sm font-bold text-amber-600 dark:text-amber-400">${{ number_format($balance, 2) }}</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $paidPct }}%"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1 text-right">{{ number_format($paidPct, 0) }}% paid</p>
        </div>
    </div>

    {{-- ── Status Update (non-pending) ─────────────────────── --}}
    @if($booking->booking_status !== 'pending')
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Update Status</h3>
        <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST" class="flex items-center gap-3">
            @csrf @method('PATCH')
            <select name="status"
                class="flex-1 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                @foreach(['pending','confirmed','cancelled','completed','fully_paid'] as $s)
                    <option value="{{ $s }}" {{ $booking->booking_status === $s ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $s)) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
                Update
            </button>
        </form>
    </div>
    @endif

    {{-- ── Travelers ────────────────────────────────────────── --}}
    @if($booking->travelers->count())
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Travelers ({{ $booking->travelers->count() }})</h3>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach($booking->travelers as $t)
            <div class="px-6 py-3 flex items-center justify-between text-sm">
                <div>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $t->trav_fn }} {{ $t->trav_mn ? $t->trav_mn . ' ' : '' }}{{ $t->trav_ln }}</span>
                    @if($t->passport_no)
                        <span class="ml-2 text-xs text-gray-400 font-mono">{{ $t->passport_no }}</span>
                    @endif
                </div>
                <span class="text-gray-500 text-xs">{{ ucfirst($t->gender) }} · {{ $t->nationality }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Payments ─────────────────────────────────────────── --}}
    @if($booking->payments->count())
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Payments ({{ $booking->payments->count() }})</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-2 text-left text-xs text-gray-500">Date</th>
                    <th class="px-6 py-2 text-left text-xs text-gray-500">Amount</th>
                    <th class="px-6 py-2 text-left text-xs text-gray-500">Method</th>
                    <th class="px-6 py-2 text-left text-xs text-gray-500">Reference</th>
                    <th class="px-6 py-2 text-left text-xs text-gray-500">Status</th>
                    <th class="px-6 py-2 text-left text-xs text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($booking->payments as $p)
                <tr>
                    <td class="px-6 py-3 text-gray-600 dark:text-gray-400 text-xs">{{ $p->payment_date->format('M d, Y') }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-900 dark:text-white">${{ number_format($p->amount_paid, 2) }}</td>
                    <td class="px-6 py-3 text-gray-600 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $p->payment_method) }}</td>
                    <td class="px-6 py-3 text-gray-500 text-xs font-mono">{{ $p->payment_ref_no }}</td>
                    <td class="px-6 py-3">
                        @php
                            $ps = [
                                'verified' => 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400',
                                'pending'  => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
                                'rejected' => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
                            ][$p->payment_status] ?? '';
                        @endphp
                        <span class="px-2 py-0.5 text-xs rounded-full {{ $ps }}">{{ ucfirst($p->payment_status) }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.payments.show', $p->id) }}"
                                class="text-xs text-sky-600 dark:text-sky-400 hover:underline">View</a>
                            @if($p->payment_status === 'pending')
                                <form action="{{ route('admin.payments.verify', $p->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-green-600 dark:text-green-400 hover:underline">Verify</button>
                                </form>
                                <form action="{{ route('admin.payments.reject', $p->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">Reject</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection
